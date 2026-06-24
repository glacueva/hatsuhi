<?php

namespace App\Filament\Widgets;

use App\Models\Views\ExpenseMovementCategoryView;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ExpenseCategoryByMonthChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Expense Categories';

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected static ?int $sort = 3;

    private array $pastelColors = [
        '#FFB2D0', '#B2FFF6', '#B2FFC3', '#FFD8B1', '#FFD9B2', '#B2BFFF',
        '#C3B2FF', '#FFB7B2', '#D8FFD8', '#FFB2E1', '#B2D0FF', '#E4FFF0',
        '#FF9AA2', '#E1F7F7', '#FFB2BF', '#F3FFE3', '#B2FFD4', '#FFDAC1',
        '#B5EAD7', '#FFCCF9', '#B286FD', '#E2F0CB', '#FFB585', '#B2F2FF',
        '#FFC4C4', '#F0E4FF', '#C7CEEA', '#B2FFE5', '#FFB2F2', '#E5B2FF',
        '#FFE758', '#8FD9FB', '#FFF0E4', '#FFDFD3', '#B5EAD7', '#D1EAFF',
        '#FFB7B2', '#D5AAFF', '#FFEAB2', '#D4B2FF', '#FFC8B2', '#FF83B2',
        '#FCE2CE', '#E1FFB2', '#FFFBB2', '#E2F0CB', '#F8C8DC', '#80FFDB',
        '#BFFCC6', '#D0FFB2', '#F2FFB2', '#B2CEFE', '#B2E1FF', '#F6B2FF',
        '#FFFFD1', '#FEE12B', '#FFD1DC', '#FFE1E1', '#E4F0FF', '#C5A3FF',
    ];

    protected function getData(): array
    {
        $selectedYear = $this->pageFilters['year'] ?? now()->year;
        $selectedMonth = $this->pageFilters['month'] ?? now()->month;
        $selectedAccount = $this->pageFilters['account'] ?? null;

        $data = ExpenseMovementCategoryView::where('month', $selectedMonth)
            ->where('year', $selectedYear)
            ->when($selectedAccount, function ($query) use ($selectedAccount): void {
                $query->where('account_id', $selectedAccount);
            })
            ->where('user_id', auth()->id())
            ->groupBy('category_id', 'name')
            ->selectRaw('sum(total_amount) as total_amount, name, category_id')
            ->get()
            ->toArray();

        $categoryIds = array_column($data, 'category_id');
        $colors = array_map(fn ($id, $index) => $this->getContrastColor($index), $categoryIds, array_keys($categoryIds));

        return [
            'datasets' => [
                [
                    'label' => 'Expense',
                    'data' => array_column($data, 'total_amount'),
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => array_column($data, 'name'),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    private function getContrastColor(int $index): string
    {
        if (count($this->pastelColors) < $index) {
            return '#CCCCCC';
        }

        return $this->pastelColors[$index];
    }
}
