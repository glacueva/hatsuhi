<?php

namespace App\Filament\Resources\MovementAlarms\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MovementAlarmForm
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
                DatePicker::make('date')
                    ->required(),
                TextInput::make('concept')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Toggle::make('is_repeatable')
                    ->required(),
                TextInput::make('periodicity_times')
                    ->numeric(),
                Select::make('periodicity_unit')
                    ->options(['year' => 'Year', 'month' => 'Month', 'day' => 'Day']),
            ]);
    }
}
