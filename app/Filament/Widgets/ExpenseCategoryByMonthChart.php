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
        $selectedAccount = Session::get('dashboard_account', null);
        
        $data = ExpenseMovementCategoryView::where('month', $selectedMonth)
            ->where('year', $selectedYear)
            ->when($selectedAccount, function ($query) use ($selectedAccount) {
                $query->where('account_id', $selectedAccount);
            })
            ->where('user_id', auth()->id())
            ->get(['total_amount', 'name', 'category_id'])
            ->toArray();

        $categoryIds = array_column($data, 'category_id');
        $colors = array_map(fn($id, $index) => $this->getContrastColor($index), $categoryIds, array_keys($categoryIds));

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
        $maxDistinct = 60;
        // Usamos el módulo para repetir después de 60
        $hueStep = 360 / $maxDistinct;
        $hue = ($index % $maxDistinct) * $hueStep;

        // Retornamos el string en formato HSL
        return "hsl($hue, 90%, 85%)";
    }
}
