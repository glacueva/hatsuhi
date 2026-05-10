<?php

namespace App\Filament\Widgets;

use App\Models\Movement;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Support\Icons\Heroicon;

class RecentMovements extends TableWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 2;
    protected static ?int $sort = 3;


    public function table(Table $table): Table
    {
        $selectedYear = $this->pageFilters['year'] ?? now()->year;
        $selectedMonth = $this->pageFilters['month'] ?? now()->month;
        $selectedAccount = $this->pageFilters['account'] ?? null;
        
        return $table
            ->query(function () use ($selectedYear, $selectedMonth, $selectedAccount): Builder {
                $query = Movement::query()
                    ->whereYear('date', $selectedYear)
                    ->whereMonth('date', $selectedMonth)
                    ->when($selectedAccount, function ($query) use ($selectedAccount) {
                        $query->where('account_id', $selectedAccount);
                    })
                    ->where('user_id', auth()->id())
                    ->latest()
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
                TextColumn::make('account.name')
                    ->label('Account')
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('concept')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money(fn() => auth()->user()->currency->short ?? 'USD')
                    ->sortable()
                    ->summarize(Sum::make()),
                TextColumn::make('shared_amount')
                    ->label('Share')
                    ->money(fn() => auth()->user()->currency->short ?? 'USD')
                    ->sortable()
                    ->summarize(Sum::make()),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc');
    }
}