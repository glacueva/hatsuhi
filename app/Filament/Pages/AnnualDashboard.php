<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AnnualExpenseBudgetRadarChart;
use App\Filament\Widgets\AnnualExpenseCategoriesChart;
use App\Filament\Widgets\AnnualIncomeBudgetRadarChart;
use App\Filament\Widgets\AnnualIncomeVsExpenseChart;
use App\Filament\Widgets\AnnualStatsOverview;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;

class AnnualDashboard extends BaseDashboard
{
    use HasFiltersAction;

    protected static string $routePath = '/annual';

    public static function getNavigationLabel(): string
    {
        return __('app.hatsuhi.annual_dashboard');
    }

    public function getTitle(): string
    {
        return __('app.hatsuhi.annual_dashboard');
    }

    public function getWidgets(): array
    {
        return [
            AnnualStatsOverview::class,
            AnnualExpenseBudgetRadarChart::class,
            AnnualIncomeBudgetRadarChart::class,
            AnnualIncomeVsExpenseChart::class,
            AnnualExpenseCategoriesChart::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->schema([
                    Select::make('account')
                        ->label(__('app.accounts.single'))
                        ->options(auth()->user()->accounts()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->placeholder(__('app.hatsuhi.all_accounts')),
                    Select::make('year')
                        ->label(__('app.hatsuhi.year'))
                        ->options(function () {
                            $currentYear = now()->year;
                            $years = [];

                            for ($year = $currentYear - 5; $year <= $currentYear + 5; $year++) {
                                $years[$year] = $year;
                            }

                            return $years;
                        })
                        ->default(now()->year),
                ]),
        ];
    }
}
