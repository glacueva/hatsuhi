<?php

namespace App\Filament\Resources\Expectations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ExpectationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category.name')
                    ->label(__('app.expectations.fields.movement_category')),
                TextEntry::make('year')
                    ->label(__('app.expectations.fields.year')),
                TextEntry::make('amount')
                    ->label(__('app.expectations.fields.amount'))
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
