<?php

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Toggle::make('is_main')
                    ->required(),
                Toggle::make('is_shared')
                    ->required(),
                TextInput::make('share')
                    ->helperText('% of shared account (if applies)')
                    ->requiredIf('is_shared', true)
                    ->numeric()
                    ->default(0.0)
                    ->maxValue(100)
                    ->minValue(0),
                TextInput::make('balance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                
            ])->columns(1);
    }
}
