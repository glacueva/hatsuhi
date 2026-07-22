<?php

namespace App\Filament\Widgets;

use App\Models\Expectation;
use App\Models\Views\ExpenseMovementView;
use App\Models\Views\IncomeMovementView;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = 2;

    protected ?string $pollingInterval = null;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();
        $currentYear = empty($this->pageFilters['year']) ? now()->year : $this->pageFilters['year'];
        $currentMonth = empty($this->pageFilters['month']) ? now()->month : $this->pageFilters['month'];
        $selectedAccount = empty($this->pageFilters['account']) ? null : $this->pageFilters['account'];

        // Regular user stats
        // TODO: Refactor to use the new views instead of raw queries
        $incomeThisMonth = IncomeMovementView::where('user_id', $user->id)
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->when($selectedAccount, function ($query) use ($selectedAccount): void {
                $query->where('account_id', $selectedAccount);
            })
            ->sum('total_amount');

        $expensesThisMonth = ExpenseMovementView::where('user_id', $user->id)
            ->where('year', $currentYear)
            ->where('month', $currentMonth)
            ->when($selectedAccount, function ($query) use ($selectedAccount): void {
                $query->where('account_id', $selectedAccount);
            })
            ->sum('total_amount');

        $savingsThisMonth = $incomeThisMonth - $expensesThisMonth;

        $incomeBudget = Expectation::where('user_id', $user->id)
            ->where('year', $currentYear)
            ->whereHas('category.movementType', fn ($q) => $q->where('is_positive', true))
            ->sum('amount');

        $incomeBudgetMonthly = $incomeBudget / 12;

        return [
            Stat::make(__('app.hatsuhi.widgets.income_this_month'), $user->currency->symbol.number_format($incomeThisMonth, 2))
                ->description($incomeBudget > 0 ? number_format(($incomeThisMonth / $incomeBudgetMonthly) * 100, 1).__('app.hatsuhi.widgets.percent_of_monthly_budget') : __('app.hatsuhi.widgets.no_budget_set'))
                ->descriptionIcon($incomeThisMonth >= $incomeBudgetMonthly ? 'heroicon-o-arrow-up' : 'heroicon-o-arrow-down')
                ->color($incomeThisMonth >= $incomeBudgetMonthly ? 'success' : 'info')
                ->icon('heroicon-o-arrow-up-circle'),

            Stat::make(__('app.hatsuhi.expense'), $user->currency->symbol.number_format($expensesThisMonth, 2))
                ->description(__('app.hatsuhi.widgets.expense_this_month'))
                ->color('info')
                ->icon('heroicon-o-arrow-down-circle'),

            Stat::make(__('app.hatsuhi.widgets.monthly_savings'), $user->currency->symbol.number_format($savingsThisMonth, 2))
                ->description($savingsThisMonth >= 0 ? __('app.hatsuhi.widgets.positive_balance') : __('app.hatsuhi.widgets.negative_balance'))
                ->color($savingsThisMonth >= 0 ? 'success' : 'info')
                ->icon($savingsThisMonth >= 0 ? 'heroicon-o-banknotes' : 'heroicon-o-exclamation-triangle'),
        ];
    }
}
