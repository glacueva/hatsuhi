<?php

namespace App\Filament\Widgets;

use App\Enums\Month;
use App\Models\Views\ExpenseExpectedView;
use App\Models\Views\ExpenseMovementView;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ExpenseBudgetVsActualExpense extends ChartWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $user = auth()->user();
        $currentYear = empty($this->pageFilters['year']) ? now()->year : $this->pageFilters['year'];
        $selectedAccount = empty($this->pageFilters['account']) ? null : $this->pageFilters['account'];

        // Get monthly budget data
        $monthlyBudgets = ExpenseExpectedView::where('user_id', $user->id)
            ->where('year', $currentYear)
            ->selectRaw('SUM(amount/12) as monthly_budget')
            ->first();

        $monthlyBudget = $monthlyBudgets->monthly_budget ?? 0;

        // Get actual monthly data
        $actuals = ExpenseMovementView::where('user_id', $user->id)
            ->where('year', $currentYear)
            ->when($selectedAccount, function ($query) use ($selectedAccount): void {
                $query->where('account_id', $selectedAccount);
            })
            ->selectRaw('month, SUM(total_amount) as total_amount')
            ->orderBy('month')
            ->groupBy('month')
            ->get()
            ->pluck('total_amount', 'month')
            ->toArray();

        $months = Month::shortOptions(onlyValues: true);

        $budgetData = [];
        $actualData = [];

        for ($i = 1; $i <= 12; $i++) {
            $budgetData[] = $monthlyBudget;
            $actualData[] = $actuals[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => __('app.hatsuhi.budget'),
                    'data' => $budgetData,
                    'borderColor' => 'lightgreen',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => __('app.hatsuhi.actual'),
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

    public function getHeading(): string
    {
        return __('app.hatsuhi.widgets.expense_budget_actual_expense');
    }
}
