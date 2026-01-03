<?php

namespace App\Filament\Resources\Movements;

use App\Filament\Resources\Movements\Pages\CreateMovement;
use App\Filament\Resources\Movements\Pages\EditMovement;
use App\Filament\Resources\Movements\Pages\ListMovements;
use App\Filament\Resources\Movements\Pages\ViewMovement;
use App\Filament\Resources\Movements\Schemas\MovementForm;
use App\Filament\Resources\Movements\Schemas\MovementInfolist;
use App\Filament\Resources\Movements\Tables\MovementsTable;
use App\Models\Movement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;


use Illuminate\Database\Eloquent\Builder;

class MovementResource extends Resource
{
    protected static ?string $model = Movement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'concept';

    public static function form(Schema $schema): Schema
    {
        return MovementForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MovementInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MovementsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMovements::route('/'),
            'create' => CreateMovement::route('/create'),
            'view' => ViewMovement::route('/{record}'),
            'edit' => EditMovement::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        return $query->where('user_id', auth()->id());
    }
}
