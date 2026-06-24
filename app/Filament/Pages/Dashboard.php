<?php

namespace App\Filament\Pages;

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
            FilterAction::make('Filter by Date')
                ->schema([
                    Select::make('account')
                        ->label('Account')
                        ->options(auth()->user()->accounts()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->placeholder('All accounts'),
                    Select::make('month')
                        ->label('Month')
                        ->options([
                            1 => 'January',
                            2 => 'February',
                            3 => 'March',
                            4 => 'April',
                            5 => 'May',
                            6 => 'June',
                            7 => 'July',
                            8 => 'August',
                            9 => 'September',
                            10 => 'October',
                            11 => 'November',
                            12 => 'December',
                        ])
                        ->default(now()->month),

                    Select::make('year')
                        ->label('Year')
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
