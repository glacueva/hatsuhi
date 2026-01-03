<?php

namespace App\Filament\Widgets;

use App\Models\Movement;
use App\Models\Expectation;
use App\Models\Views\ExpenseMovementView;
use App\Models\Views\IncomeMovementView;

use Filament\Widgets\ChartWidget;

use Illuminate\Support\Facades\Session;

class ActualIncomeVsActualExpense extends ChartWidget
{
    protected ?string $heading = 'Actual Income Vs Actual Expense';
    protected $listeners = ['refreshDashboard' => '$refresh'];
    protected int | string | array $columnSpan = 1;
    protected static ?int $sort = 2;


    protected function getData(): array
    {
        $user = auth()->user();
        $currentYear = Session::get('dashboard_year', now()->year);
        
        // Get monthly budget data
        $income_actuals = IncomeMovementView::where('user_id', $user->id)
            ->where('year', $currentYear)
            ->select('month', 'total_amount')
            ->orderBy('month')
            ->get()
            ->pluck('total_amount', 'month')
            ->toArray();
        
        // Get actual monthly data
        $actuals = ExpenseMovementView::where('user_id', $user->id)
            ->where('year', $currentYear)
            ->select('month', 'total_amount')
            ->orderBy('month')
            ->get()
            ->pluck('total_amount', 'month')
            ->toArray();
        
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        $budgetData = [];
        $actualData = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $budgetData[] = $income_actuals[$i] ?? 0;
            $actualData[] = $actuals[$i] ?? 0;
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Income',
                    'data' => $budgetData,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.1,
                ],
                [
                    'label' => 'Expense',
                    'data' => $actualData,
                    'borderColor' => 'rgb(34, 197, 94)',
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
}
