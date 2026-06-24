<?php

namespace App\Filament\Resources\MovementCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MovementCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('movement_type_id')
                    ->relationship(
                        'movementType',
                        'name',
                        fn ($query) => $query->where('user_id', auth()->id())
                    )
                    ->required(),
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
