<?php

namespace App\Filament\Widgets;

use App\Models\Views\ExpenseMovementCategoryView;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class AnnualExpenseCategoriesChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $year = empty($this->pageFilters['year']) ? now()->year : $this->pageFilters['year'];
        $accountId = empty($this->pageFilters['account']) ? null : $this->pageFilters['account'];

        $categories = ExpenseMovementCategoryView::where('user_id', auth()->id())
            ->where('year', $year)
            ->when($accountId, fn ($query) => $query->where('account_id', $accountId))
            ->groupBy('category_id', 'name')
            ->selectRaw('category_id, name, SUM(total_amount) as total_amount')
            ->havingRaw('SUM(total_amount) > 0')
            ->orderByRaw('SUM(total_amount) DESC')
            ->get();

        $colors = [
            '#0F766E', '#D97706', '#2563EB', '#DB2777', '#65A30D', '#7C3AED',
            '#0891B2', '#EA580C', '#4F46E5', '#16A34A', '#C026D3', '#CA8A04',
        ];

        return [
            'datasets' => [
                [
                    'label' => __('app.hatsuhi.expense'),
                    'data' => $categories->pluck('total_amount')->all(),
                    'backgroundColor' => $categories->keys()->map(fn ($index) => $colors[$index % count($colors)])->all(),
                ],
            ],
            'labels' => $categories->pluck('name')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    public function getHeading(): string
    {
        return __('app.hatsuhi.widgets.annual_expense_categories');
    }
}
