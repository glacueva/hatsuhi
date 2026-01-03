<?php

namespace App\Filament\Widgets;

use App\Models\MovementAlarm;
use App\Models\Movement;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;

class UpcomingAlarms extends TableWidget
{
    protected static ?string $heading = 'Upcoming Alarms';

    public string $activeTab = 'one-time';
    public int $perPage = 10;
    protected int | string | array $columnSpan = 2;
    protected static ?int $sort = 0;

    public function mount(): void
    {
        $this->perPage = session('alarms_per_page', 10);
    }

    public function updatedPerPage($value): void
    {
        session(['alarms_per_page' => $value]);
    }

    public function getSelectedYear(): int
    {
        return Session::get('dashboard_year', now()->year);
    }

    public function getSelectedMonth(): int
    {
        return Session::get('dashboard_month', now()->month);
    }

    public function getMonthName(): string
    {
        return Carbon::create()
            ->setYear($this->getSelectedYear())
            ->setMonth($this->getSelectedMonth())
            ->format('F Y');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => $this->activeTab === 'one-time'
                ? $this->getOneTimeQuery()
                : $this->getRecurringQuery()
            )
            ->heading('Upcoming Alarms - ' . date('F Y', mktime(0, 0, 0, $this->getSelectedMonth(), 1, $this->getSelectedYear())))
            ->columns([
                TextColumn::make('concept')
                    ->label('Concept')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money(fn ($record) => $record->user->currency->short)
                    ->sortable(),

                TextColumn::make('date_display')
                    ->label('Date')
                    ->sortable(),

                BadgeColumn::make('status_badge')
                    ->label('Status')
                    ->colors([
                        'danger' => 'Due today',
                        'warning' => 'In',
                        'success' => 'In',
                    ])
                    ->formatStateUsing(fn ($state) => $state),
            ])
            ->actions([
                Action::make('execute')
                    ->label('Execute')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $this->executeAlarm($record->id)),
            ])
            ->headerActions([
                Action::make('selectMonthYear')
                    ->color('primary')
                    ->label('Select Month / Year')
                    ->form([ 
                        Select::make('month')
                            ->options(
                                collect(range(1, 12))->mapWithKeys(fn ($m) => [ $m => \Carbon\Carbon::create()->month($m)->format('F') ])
                            )
                            ->default($this->getSelectedMonth())
                            ->reactive()
                            ->afterStateUpdated(fn ($state) => $this->updateMonth($state)), 
                        Select::make('year') ->label('Year') ->options(collect(range(now()->year - 5, now()->year + 5))->mapWithKeys(fn ($y) => [$y => $y])) ->default($this->getSelectedYear()) ->reactive() ->afterStateUpdated(fn ($state) => $this->updateYear($state)), 
                    ]),
                Action::make('switchOneTime')
                    ->label('One-time')
                    ->color($this->activeTab === 'one-time' ? 'primary' : 'gray')
                    ->action(fn () => $this->activeTab = 'one-time'),

                Action::make('switchRecurring')
                    ->label('Recurring')
                    ->color($this->activeTab === 'recurring' ? 'primary' : 'gray')
                    ->action(fn () => $this->activeTab = 'recurring'),
            ])
            ->paginated([5, 10, 20, 50])
            ->defaultPaginationPageOption($this->perPage);
    }

    public function updateMonth($value)
    {
        Session::put('dashboard_month', $value);

        $this->dispatch('$refresh');
        $this->dispatch('refreshDashboard');
    }

    public function updateYear($value)
    {
        Session::put('dashboard_year', $value);

        $this->dispatch('$refresh');
        $this->dispatch('refreshDashboard');
    }


    private function getOneTimeQuery()
    {
        return MovementAlarm::query()
            ->where('user_id', auth()->id())
            ->where('is_repeatable', false)
            ->whereYear('date', $this->getSelectedYear())
            ->whereMonth('date', $this->getSelectedMonth())
            ->with('category')
            ->orderBy('date')
            ->select('*')
            ->selectRaw('date as date_display')
            ->selectRaw('"" as status_badge');
    }

    private function getRecurringQuery()
    {
        $alarms = MovementAlarm::query()
            ->where('user_id', auth()->id())
            ->where('is_repeatable', true)
            ->with('category')
            ->get()
            ->filter(fn ($alarm) =>
                $this->hasOccurrenceInMonth($alarm, $this->getSelectedYear(), $this->getSelectedMonth())
            )
            ->map(function ($alarm) {
                $date = $this->getNextOccurrence($alarm, $this->getSelectedYear(), $this->getSelectedMonth());
                $alarm->date_display = $date;
                $alarm->status_badge = $this->getStatusBadge($date);
                return $alarm;
            });

        return MovementAlarm::query()->whereIn('id', $alarms->pluck('id'));
    }

    private function getStatusBadge($date)
    {
        if (!$date) return 'No occurrence';

        $days = Carbon::parse($date)->diffInDays(now(), false) * -1;

        return match (true) {
            $days === 0 => 'Due today',
            $days <= 7 => "In $days days",
            default => "In $days days",
        };
    }

    private function hasOccurrenceInMonth($alarm, $year, $month): bool
    {
        $start = Carbon::parse($alarm->date);
        $selected = Carbon::create($year, $month, 1);
        $end = $selected->copy()->endOfMonth();

        if ($start->gt($end)) return false;

        return match ($alarm->periodicity_unit) {
            'day' => true,
            'month' => true,
            'year' => true,
            default => false,
        };
    }

    public function getNextOccurrence($alarm, $year, $month): ?string
    {
        $start = Carbon::parse($alarm->date);
        $selected = Carbon::create($year, $month, 1);
        $end = $selected->copy()->endOfMonth();

        if ($start->gt($end)) return null;

        $current = $start->copy();

        while ($current->lt($selected->startOfMonth())) {
            $current = $this->advance($current, $alarm, $start);
        }

        while ($current->lte($end)) {
            if ($current->year == $selected->year && $current->month == $selected->month) {
                return $current->toDateString();
            }
            $current = $this->advance($current, $alarm, $start);
        }

        return null;
    }

    private function advance($current, $alarm, $start)
    {
        return match ($alarm->periodicity_unit) {
            'day' => $current->addDays($alarm->periodicity_times),
            'month' => tap($current->addMonths($alarm->periodicity_times), function ($c) use ($start) {
                $c->day = min($start->day, $c->daysInMonth);
            }),
            'year' => $current->addYears($alarm->periodicity_times),
        };
    }

    public function executeAlarm($alarmId): void
    {
        $alarm = MovementAlarm::findOrFail($alarmId);

        Movement::create([
            'user_id' => $alarm->user_id,
            'movement_category_id' => $alarm->movement_category_id,
            'date' => now()->toDateString(),
            'concept' => $alarm->concept,
            'amount' => $alarm->amount,
        ]);

        if (!$alarm->is_repeatable) {
            $alarm->delete();
            session()->flash('success', 'Movement created and one-time alarm removed.');
        } else {
            session()->flash('success', 'Movement created for recurring alarm.');
        }

        $this->dispatch('$refresh');
    }
}
