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
                    ->required()
                    ->label(__('app.accounts.fields.name')),
                Toggle::make('is_main')
                    ->required()
                    ->label(__('app.accounts.fields.is_main')),
                Toggle::make('is_shared')
                    ->required()
                    ->label(__('app.accounts.fields.is_shared')),
                TextInput::make('share')
                    ->label(__('app.accounts.fields.share'))
                    ->helperText(__('app.accounts.fields.share_helper'))
                    ->requiredIf('is_shared', true)
                    ->numeric()
                    ->default(100)
                    ->maxValue(100)
                    ->minValue(0),
                TextInput::make('balance')
                    ->label(__('app.accounts.fields.balance'))
                    ->hidden() // TODO: Remove this line when the balance is calculated automatically
                    ->required()
                    ->numeric()
                    ->default(0.0),

            ])->columns(1);
    }
}
