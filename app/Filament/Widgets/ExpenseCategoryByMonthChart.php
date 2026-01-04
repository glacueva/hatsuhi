<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

use App\Models\Views\ExpenseMovementCategoryView;
use Illuminate\Support\Facades\Session;

class ExpenseCategoryByMonthChart extends ChartWidget
{
    protected ?string $heading = 'Expense Categories';
    protected $listeners = ['refreshDashboard' => '$refresh'];
    protected int | string | array $columnSpan = 1;
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $selectedYear = Session::get('dashboard_year', now()->year);
        $selectedMonth = Session::get('dashboard_month', now()->month);
        $data = ExpenseMovementCategoryView::where('month', $selectedMonth)
            ->where('year', $selectedYear)
            ->where('user_id', auth()->id())
            ->pluck('total_amount', 'name')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Expense',
                    'data' => array_values($data), 
                    'backgroundColor' => $this->getRandomRedColorsFromDataset( $data ),
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    private function getRandomRedColorsFromDataset(array $data): array
    {
        $colors = [];
        foreach($data as $value) {
            $colors[] = 'rgba(255, ' . $value%128 . ', ' . $value%256 . ')';
        }

        return $colors;
    }
}
