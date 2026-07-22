<?php

namespace App\Filament\Widgets;

use App\Enums\Month;
use App\Models\Views\ExpenseMovementView;
use App\Models\Views\IncomeMovementView;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ActualIncomeVsActualExpense extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Income vs Expense';

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $user = auth()->user();
        $currentYear = empty($this->pageFilters['year']) ? now()->year : $this->pageFilters['year'];
        $selectedAccount = empty($this->pageFilters['account']) ? null : $this->pageFilters['account'];
        // Get monthly budget data
        $income_actuals = IncomeMovementView::where('user_id', $user->id)
            ->where('year', $currentYear)
            ->when($selectedAccount, function ($query) use ($selectedAccount): void {
                $query->where('account_id', $selectedAccount);
            })
            ->select('month', 'total_amount')
            ->orderBy('month')
            ->get()
            ->pluck('total_amount', 'month')
            ->toArray();

        // Get actual monthly data
        $actuals = ExpenseMovementView::where('user_id', $user->id)
            ->where('year', $currentYear)
            ->when($selectedAccount, function ($query) use ($selectedAccount): void {
                $query->where('account_id', $selectedAccount);
            })
            ->select('month', 'total_amount')
            ->orderBy('month')
            ->get()
            ->pluck('total_amount', 'month')
            ->toArray();

        $months = Month::shortOptions(onlyValues: true);

        $budgetData = [];
        $actualData = [];

        for ($i = 1; $i <= 12; $i++) {
            $budgetData[] = $income_actuals[$i] ?? 0;
            $actualData[] = $actuals[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => __('app.hatsuhi.income'),
                    'data' => $budgetData,
                    'borderColor' => 'lightgreen',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.1,
                ],
                [
                    'label' => __('app.hatsuhi.expense'),
                    'data' => $actualData,
                    'borderColor' => 'fuchsia',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                    'tension' => 0.1,
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
        return __('app.hatsuhi.widgets.actual_income_actual_expense');
    }
}
