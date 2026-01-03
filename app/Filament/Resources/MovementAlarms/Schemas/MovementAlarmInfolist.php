<?php

namespace App\Filament\Resources\MovementAlarms\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MovementAlarmInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category.name')
                    ->label('Category'),
                TextEntry::make('date')
                    ->date()->label('Start'),
                TextEntry::make('concept'),
                TextEntry::make('amount')
                    ->numeric(),
                IconEntry::make('is_repeatable')
                    ->boolean(),
                TextEntry::make('periodicity_times')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('periodicity_unit')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
