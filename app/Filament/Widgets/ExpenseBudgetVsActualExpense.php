<?php

namespace App\Filament\Widgets;

use App\Models\Movement;
use App\Models\Expectation;
use App\Models\Views\ExpenseMovementView;
use App\Models\Views\ExpenseExpectedView;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ExpenseBudgetVsActualExpense extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Expense Budget Vs Actual Expense';
    protected int | string | array $columnSpan = 1;
    protected ?string $pollingInterval = null;
    protected static ?int $sort = 2;


    protected function getData(): array
    {
        $user = auth()->user();
        $currentYear = $this->pageFilters['year'] ?? now()->year;
        $selectedAccount = $this->pageFilters['account'] ?? null;

        // Get monthly budget data
        $monthlyBudgets = ExpenseExpectedView::where('user_id', $user->id)
            ->where('year', $currentYear)
            ->selectRaw('SUM(amount/12) as monthly_budget')
            ->first();
            
        $monthlyBudget = $monthlyBudgets->monthly_budget ?? 0;
        
        // Get actual monthly data
        $actuals = ExpenseMovementView::where('user_id', $user->id)
            ->where('year', $currentYear)
            ->when($selectedAccount, function ($query) use ($selectedAccount) {
                $query->where('account_id', $selectedAccount);
            })
            ->select('month', 'total_amount')
            ->orderBy('month')
            ->get()
            ->pluck('total_amount', 'month')
            ->toArray();
        
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        $budgetData = [];
        $actualData = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $budgetData[] = $monthlyBudget;
            $actualData[] = $actuals[$i] ?? 0;
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Budget',
                    'data' => $budgetData,
                    'borderColor' => 'lightgreen',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Actual',
                    'data' => $actualData,
                    'borderColor' => 'fuchsia',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
