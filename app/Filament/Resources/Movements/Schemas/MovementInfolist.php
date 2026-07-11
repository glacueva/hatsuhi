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
                    ->label(__('app.movements.fields.account')),
                TextEntry::make('category.name')
                    ->label(__('app.movements.fields.category')),
                TextEntry::make('date')
                    ->label(__('app.movements.fields.date'))
                    ->date(),
                TextEntry::make('concept')
                    ->label(__('app.movements.fields.concept')),
                TextEntry::make('amount')
                    ->label(__('app.movements.fields.amount'))
                    ->numeric(),
                TextEntry::make('share')
                    ->label(__('app.movements.fields.share'))
                    ->numeric(),
                TextEntry::make('shared_amount')
                    ->label(__('app.movements.fields.shared_amount'))
                    ->numeric(),
                IconEntry::make('is_compensation')
                    ->boolean()
                    ->label(__('app.movements.fields.is_compensation')),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
