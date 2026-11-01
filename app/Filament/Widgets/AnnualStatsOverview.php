<?php

namespace App\Filament\Widgets;

use App\Models\Views\ExpenseMovementView;
use App\Models\Views\IncomeMovementView;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnnualStatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = 2;

    protected ?string $pollingInterval = null;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();
        $year = empty($this->pageFilters['year']) ? now()->year : $this->pageFilters['year'];
        $accountId = empty($this->pageFilters['account']) ? null : $this->pageFilters['account'];

        $income = IncomeMovementView::where('user_id', $user->id)
            ->where('year', $year)
            ->when($accountId, fn ($query) => $query->where('account_id', $accountId))
            ->sum('total_amount');

        $expenses = ExpenseMovementView::where('user_id', $user->id)
            ->where('year', $year)
            ->when($accountId, fn ($query) => $query->where('account_id', $accountId))
            ->sum('total_amount');

        $savings = $income - $expenses;

        return [
            Stat::make(__('app.hatsuhi.widgets.annual_income'), $user->currency->symbol.number_format($income, 2))
                ->icon('heroicon-o-arrow-up-circle')
                ->color('success'),
            Stat::make(__('app.hatsuhi.widgets.annual_expense'), $user->currency->symbol.number_format($expenses, 2))
                ->icon('heroicon-o-arrow-down-circle')
                ->color('info'),
            Stat::make(__('app.hatsuhi.widgets.annual_savings'), $user->currency->symbol.number_format($savings, 2))
                ->description($savings >= 0 ? __('app.hatsuhi.widgets.positive_balance') : __('app.hatsuhi.widgets.negative_balance'))
                ->icon($savings >= 0 ? 'heroicon-o-banknotes' : 'heroicon-o-exclamation-triangle')
                ->color($savings >= 0 ? 'success' : 'info'),
        ];
    }
}
