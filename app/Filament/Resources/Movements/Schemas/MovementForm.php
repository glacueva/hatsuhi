<?php

namespace App\Filament\Resources\Movements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use App\Models\MovementCategory;

class MovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('movement_category_id')
                    ->options(function () {
                        return MovementCategory::query()
                            ->where('user_id', auth()->id())
                            ->with('movementType')
                            ->get()
                            ->groupBy(fn ($record) => $record->movementType->name) 
                            ->map(fn ($group) => $group->pluck('name', 'id'))
                            ->toArray();
                    })
                    ->searchable()
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
                    ->helperText('A compensation happens when a return happens: e.g you have to return money from a previous Income Movement and you do not want to edit that Movement. It happens also the other way round, a previous Expense is compensated for example the return of a purchase.')
                    ->default(false)
                    ->required(),
            ]);
    }
}
