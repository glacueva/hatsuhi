<?php

namespace App\Filament\Widgets;

use App\Models\Views\FlowMovementsView;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Support\Icons\Heroicon;

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class RecentMovements extends TableWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 2;
    protected ?string $pollingInterval = null;
    protected static ?int $sort = 3;


    public function table(Table $table): Table
    {
        $selectedYear = $this->pageFilters['year'] ?? now()->year;
        $selectedMonth = $this->pageFilters['month'] ?? now()->month;
        $selectedAccount = $this->pageFilters['account'] ?? null;
        
        return $table
            ->query(function () use ($selectedYear, $selectedMonth, $selectedAccount): Builder {
                $query = FlowMovementsView::query()
                    ->whereYear('date', $selectedYear)
                    ->whereMonth('date', $selectedMonth)
                    ->when($selectedAccount, function ($query) use ($selectedAccount) {
                        $query->where('account_id', $selectedAccount);
                    })
                    ->where('user_id', auth()->id())
                    ->orderBy('date', 'desc')
                    ->limit(30);

                return $query;
            })
            ->heading('Recent Movements - ' . date('F Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)))
            ->columns([
                IconColumn::make('positive_flow')
                    ->label('Flow')
                    ->icon(
                        fn($state) => $state ? Heroicon::ArrowTrendingUp : Heroicon::ArrowTrendingDown
                    )
                    ->color(fn (bool $state): string => match ($state) {
                        true => 'success',
                        false => 'info',
                    })
                    ->toggleable(false),
                TextColumn::make('account_name')
                    ->label('Account')
                    ->sortable(),
                TextColumn::make('category_name')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('concept')
                    ->searchable(),
                TextColumn::make('amount')
                    ->money(fn($record) => $record?->currency_short ?? 'EUR')
                    ->sortable()
                    ->summarize(
                        Sum::make('amount')
                            ->hiddenLabel()
                            ->money(auth()->user()->currency->short)
                    ),
                TextColumn::make('shared_amount')
                    ->label('Share')
                    ->money(fn($record) => $record?->currency_short ?? 'EUR')
                    ->sortable()
                    ->summarize(
                        Sum::make('shared_amount')
                            ->hiddenLabel()
                            ->money(auth()->user()->currency->short)
                    ),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id') // Use the foreign key column name
                    ->label('Category')
                    ->preload()
                    ->relationship('category', 'name'),
                Filter::make('date')
                    ->form([
                        DatePicker::make('date_from'),
                        DatePicker::make('date_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when($data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })

            ])
            ->defaultSort('date', 'desc');
    }
}