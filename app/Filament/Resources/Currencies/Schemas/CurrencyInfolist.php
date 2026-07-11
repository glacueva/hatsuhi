<?php

namespace App\Filament\Resources\Currencies\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CurrencyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label(__('app.currencies.fields.name')),
                TextEntry::make('short')
                    ->label(__('app.currencies.fields.short')),
                TextEntry::make('symbol')
                    ->label(__('app.currencies.fields.symbol')),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->label(__('app.users.fields.created_at')),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->label(__('app.users.fields.updated_at')),
            ]);
    }
}
