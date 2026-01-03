<?php

namespace App\Filament\Resources\MovementTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MovementTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Income, Expense, Investment'),
                Toggle::make('is_positive')
                    ->label('Positive Movement')
                    ->helperText('Toggle ON for income, OFF for expense')
                    ->default(true)
                    ->required(),
            ]);
    }
}
