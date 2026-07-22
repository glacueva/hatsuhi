<?php

namespace App\Filament\Pages;

use App\Enums\Month;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;

class Dashboard extends BaseDashboard
{
    use HasFiltersAction;

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
                    Select::make('month')
                        ->label(__('app.hatsuhi.month'))
                        ->options(Month::options())
                        ->default(now()->month),

                    Select::make('year')
                        ->label(__('app.hatsuhi.year'))
                        ->options(function () {
                            $currentYear = now()->year;
                            $years = [];

                            for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
                                $years[$i] = $i;
                            }

                            return $years;
                        })
                        ->default(now()->year),
                ]),
        ];
    }
}
