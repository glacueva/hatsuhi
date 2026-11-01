<?php

namespace App\Filament\Widgets;

use App\Enums\Month;
use App\Models\Views\ExpenseMovementView;
use App\Models\Views\IncomeMovementView;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class AnnualIncomeVsExpenseChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $year = empty($this->pageFilters['year']) ? now()->year : $this->pageFilters['year'];
        $accountId = empty($this->pageFilters['account']) ? null : $this->pageFilters['account'];
        $monthlyTotals = [];

        foreach ([IncomeMovementView::class, ExpenseMovementView::class] as $model) {
            $monthlyTotals[] = $model::where('user_id', auth()->id())
                ->where('year', $year)
                ->when($accountId, fn ($query) => $query->where('account_id', $accountId))
                ->selectRaw('month, SUM(total_amount) as total_amount')
                ->groupBy('month')
                ->pluck('total_amount', 'month')
                ->toArray();
        }

        return [
            'datasets' => [
                [
                    'label' => __('app.hatsuhi.income'),
                    'data' => array_map(fn ($month) => $monthlyTotals[0][$month] ?? 0, range(1, 12)),
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.15)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => __('app.hatsuhi.expense'),
                    'data' => array_map(fn ($month) => $monthlyTotals[1][$month] ?? 0, range(1, 12)),
                    'borderColor' => 'rgb(217, 70, 239)',
                    'backgroundColor' => 'rgba(217, 70, 239, 0.12)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => Month::shortOptions(onlyValues: true),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getHeading(): string
    {
        return __('app.hatsuhi.widgets.annual_income_expense');
    }
}
