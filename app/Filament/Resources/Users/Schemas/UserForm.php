<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label(__('app.users.fields.email'))
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at')
                    ->label(__('app.users.fields.email_verified_at')),
                TextInput::make('password')
                    ->label(__('app.users.fields.password'))
                    ->password()
                    ->required(),
                Select::make('currency_id')
                    ->label(__('app.users.fields.currency'))
                    ->relationship('currency', 'name'),
                Toggle::make('is_admin')
                    ->label(__('app.users.fields.is_admin'))
                    ->required(),
            ]);
    }
}
