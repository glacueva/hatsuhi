<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use App\Models\Movement;
use App\Models\Expectation;

class StatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 2;
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();
        $currentYear = $this->pageFilters['year'] ?? now()->year;
        $currentMonth = $this->pageFilters['month'] ?? now()->month;
        $selectedAccount = $this->pageFilters['account'] ?? null;
        
        // Regular user stats
        $incomeThisMonth = Movement::where('user_id', $user->id)
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->when($selectedAccount, function ($query) use ($selectedAccount) {
                $query->where('account_id', $selectedAccount);
            })
            ->whereHas('category.movementType', fn($q) => $q->where('is_positive', true))
            ->sum('amount');
            
        $expensesThisMonth = Movement::where('user_id', $user->id)
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->when($selectedAccount, function ($query) use ($selectedAccount) {
                $query->where('account_id', $selectedAccount);
            })
            ->whereHas('category.movementType', fn($q) => $q->where('is_positive', false))
            ->sum('amount');
            
        $savingsThisMonth = $incomeThisMonth - $expensesThisMonth;
        
        $incomeBudget = Expectation::where('user_id', $user->id)
            ->where('year', $currentYear)
            ->whereHas('category.movementType', fn($q) => $q->where('is_positive', true))
            ->sum('amount');
            
        $incomeBudgetMonthly = $incomeBudget / 12;
        
        return [
            Stat::make('Income This Month', $user->currency->symbol . number_format($incomeThisMonth, 2))
                ->description($incomeBudget > 0 ? number_format(($incomeThisMonth / $incomeBudgetMonthly) * 100, 1) . '% of monthly budget' : 'No budget set')
                ->descriptionIcon($incomeThisMonth >= $incomeBudgetMonthly ? 'heroicon-o-arrow-up' : 'heroicon-o-arrow-down')
                ->color($incomeThisMonth >= $incomeBudgetMonthly ? 'success' : 'info')
                ->icon('heroicon-o-arrow-up-circle'),
            
            Stat::make('Expenses This Month', $user->currency->symbol . number_format($expensesThisMonth, 2))
                ->description('Total spent this month')
                ->color('info')
                ->icon('heroicon-o-arrow-down-circle'),
            
            Stat::make('Monthly Savings', $user->currency->symbol . number_format($savingsThisMonth, 2))
                ->description($savingsThisMonth >= 0 ? 'Positive balance' : 'Negative balance')
                ->color($savingsThisMonth >= 0 ? 'success' : 'info')
                ->icon($savingsThisMonth >= 0 ? 'heroicon-o-banknotes' : 'heroicon-o-exclamation-triangle'),
        ];
    }
}
