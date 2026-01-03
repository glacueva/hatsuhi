<?php

namespace App\Filament\Resources\Expectations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;

class ExpectationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('movement_category_id')
                    ->relationship(
                        'category',
                        'name',
                        fn ($query) => $query->where('user_id', auth()->id())
                    )
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('year')
                    ->required()
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->default(now()->year)
                    ->suffixIcon('heroicon-o-calendar'),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix(function ($get) {
                        $symbol = auth()->user()->currency->symbol ?? '$';
                        return $symbol;
                    })
                    ->suffix(function ($get) {
                        $amount = $get('amount');
                        return $amount ? '/year' : '';
                    })
                    ->rules(['min:0.01']),
                Placeholder::make('monthly_amount')
                    ->label('Monthly Amount')
                    ->content(function ($get) {
                        $amount = $get('amount');
                        $symbol = auth()->user()->currency->symbol ?? '$';
                        return $amount ? $symbol . number_format($amount / 12, 2) . '/month' : 'N/A';
                        })
            ])
            ->columns(2);
    }
}
