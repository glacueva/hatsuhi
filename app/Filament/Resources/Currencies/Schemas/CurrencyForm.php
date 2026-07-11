<?php

namespace App\Filament\Resources\Currencies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CurrencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label(__('app.currencies.fields.name')),
                TextInput::make('short')
                    ->required()
                    ->label(__('app.currencies.fields.short')),
                TextInput::make('symbol')
                    ->required()
                    ->label(__('app.currencies.fields.symbol')),
            ]);
    }
}
