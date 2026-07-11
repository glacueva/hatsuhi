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
                    ->placeholder(__('app.movement_types.fields.name_placeholder')),
                Toggle::make('is_positive')
                    ->label(__('app.movement_types.fields.is_positive'))
                    ->helperText(__('app.movement_types.fields.is_positive_helper'))
                    ->default(false)
                    ->required(),
            ]);
    }
}
