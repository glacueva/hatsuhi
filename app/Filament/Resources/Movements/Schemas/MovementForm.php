<?php

namespace App\Filament\Resources\Movements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MovementForm
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
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->movementType->name} - {$record->name}")
                    ->searchable(['name'])
                    ->preload()
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TextInput::make('concept')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Monthly salary, Grocery shopping'),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->rules(['min:0.01']),
                Toggle::make('compensation')
                    ->label('Is it a Compensation?')
                    ->helperText('A compensation happens when a return happens: e.g you have to return money from a previous Income Movement and you do not want to edit that M.ovement. It happens also the other way round, a previous Expense is compensated for example the return of a purchase.')
                    ->default(false)
                    ->required(),
            ]);
    }
}
