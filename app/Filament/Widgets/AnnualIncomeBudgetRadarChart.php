<?php

namespace App\Filament\Widgets;

use App\Models\Views\IncomeExpectedView;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;

class AnnualIncomeBudgetRadarChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $year = empty($this->pageFilters['year']) ? now()->year : $this->pageFilters['year'];
        $accountId = empty($this->pageFilters['account']) ? null : $this->pageFilters['account'];

        $budgets = IncomeExpectedView::where('user_id', auth()->id())
            ->where('year', $year)
            ->selectRaw('category_id, category, SUM(amount) as budget_amount')
            ->groupBy('category_id', 'category')
            ->get()
            ->keyBy('category_id');

        $actuals = DB::table('movements as m')
            ->join('movement_categories as mc', 'm.movement_category_id', '=', 'mc.id')
            ->join('movement_types as mt', 'mc.movement_type_id', '=', 'mt.id')
            ->where('m.user_id', auth()->id())
            ->whereYear('m.date', $year)
            ->where(function ($query): void {
                $query
                    ->where(fn ($query) => $query->where('mt.is_positive', true)->where('m.is_compensation', false))
                    ->orWhere(fn ($query) => $query->where('mt.is_positive', false)->where('m.is_compensation', true));
            })
            ->when($accountId, fn ($query) => $query->where('m.account_id', $accountId))
            ->selectRaw('mc.id as category_id, mc.name as category, SUM(m.shared_amount) as actual_amount')
            ->groupBy('mc.id', 'mc.name')
            ->get()
            ->keyBy('category_id');

        $categoryIds = $budgets->keys()->merge($actuals->keys())->unique();

        return [
            'datasets' => [
                [
                    'label' => __('app.hatsuhi.budget'),
                    'data' => $categoryIds->map(fn ($id) => (float) ($budgets[$id]->budget_amount ?? 0))->all(),
                    'borderColor' => 'rgb(37, 99, 235)',
                    'backgroundColor' => 'rgb(37, 99, 235)',
                    'fill' => false,
                    'tension' => 0.3,
                ],
                [
                    'label' => __('app.hatsuhi.actual'),
                    'data' => $categoryIds->map(fn ($id) => (float) ($actuals[$id]->actual_amount ?? 0))->all(),
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgb(34, 197, 94)',
                    'fill' => false,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $categoryIds->map(fn ($id) => $budgets[$id]->category ?? $actuals[$id]->category)->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'type' => 'category',
                    'title' => [
                        'display' => true,
                        'text' => __('app.categories.title'),
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => __('app.movements.fields.amount'),
                    ],
                ],
            ],
        ];
    }

    public function getHeading(): string
    {
        return __('app.hatsuhi.widgets.annual_income_budget_actual');
    }

    public function getDescription(): string
    {
        return __('app.hatsuhi.widgets.budget_not_account_specific');
    }
}
