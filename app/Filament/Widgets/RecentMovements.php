<?php

namespace App\Filament\Widgets;

use App\Enums\Month;
use App\Models\Views\FlowMovementsView;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentMovements extends TableWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = 2;

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
                    ->when($selectedAccount, function ($query) use ($selectedAccount): void {
                        $query->where('account_id', $selectedAccount);
                    })
                    ->where('user_id', auth()->id())
                    ->orderBy('date', 'desc')
                    ->limit(30);

                return $query;
            })
            ->heading(__('app.hatsuhi.widgets.recent_movements').' - '.Month::tryFrom($selectedMonth)?->label().' '.$selectedYear)
            ->columns([
                IconColumn::make('positive_flow')
                    ->label(__('app.hatsuhi.flow'))
                    ->icon(
                        fn ($state) => $state ? Heroicon::ArrowTrendingUp : Heroicon::ArrowTrendingDown
                    )
                    ->color(fn (bool $state): string => match ($state) {
                        true => 'success',
                        false => 'info',
                    })
                    ->toggleable(false),
                TextColumn::make('account_name')
                    ->label(__('app.movements.fields.account'))
                    ->sortable(),
                TextColumn::make('category_name')
                    ->label(__('app.movements.fields.category'))
                    ->sortable(),
                TextColumn::make('date')
                    ->label(__('app.movements.fields.date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('concept')
                    ->label(__('app.movements.fields.concept'))
                    ->searchable(),
                TextColumn::make('amount')
                    ->money(fn ($record) => $record?->currency_short ?? 'EUR')
                    ->label(__('app.movements.fields.amount'))
                    ->sortable()
                    ->summarize(
                        Sum::make('amount')
                            ->hiddenLabel()
                            ->money(auth()->user()->currency->short)
                    ),
                TextColumn::make('shared_amount')
                    ->label(__('app.movements.fields.shared_amount'))
                    ->money(fn ($record) => $record?->currency_short ?? 'EUR')
                    ->sortable()
                    ->summarize(
                        Sum::make('shared_amount')
                            ->hiddenLabel()
                            ->money(auth()->user()->currency->short)
                    ),
            ])
            ->filters([
                SelectFilter::make('category_id') // Use the foreign key column name
                    ->label(__('app.movements.fields.category'))
                    ->preload()
                    ->relationship('category', 'name'),
                Filter::make('date')
                    ->form([
                        DatePicker::make('date_from')->label(__('app.hatsuhi.widgets.date_from')),
                        DatePicker::make('date_until')->label(__('app.hatsuhi.widgets.date_until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when($data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),

            ])
            ->defaultSort('date', 'desc');
    }
}
