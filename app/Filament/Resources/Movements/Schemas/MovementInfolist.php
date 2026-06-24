<?php

namespace App\Filament\Resources\Movements\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MovementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('account.name')
                    ->label('Account'),
                TextEntry::make('category.name')
                    ->label('Category'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('concept'),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('share')
                    ->numeric(),
                TextEntry::make('shared_amount')
                    ->numeric(),
                IconEntry::make('is_compensation')
                    ->boolean()
                    ->label('Compensation'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
