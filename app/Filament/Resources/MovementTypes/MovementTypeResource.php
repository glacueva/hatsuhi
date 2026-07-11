<?php

namespace App\Filament\Resources\MovementTypes;

use App\Filament\Resources\MovementTypes\Pages\CreateMovementType;
use App\Filament\Resources\MovementTypes\Pages\EditMovementType;
use App\Filament\Resources\MovementTypes\Pages\ListMovementTypes;
use App\Filament\Resources\MovementTypes\Pages\ViewMovementType;
use App\Filament\Resources\MovementTypes\Schemas\MovementTypeForm;
use App\Filament\Resources\MovementTypes\Schemas\MovementTypeInfolist;
use App\Filament\Resources\MovementTypes\Tables\MovementTypesTable;
use App\Models\MovementType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class MovementTypeResource extends Resource
{
    protected static ?string $model = MovementType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $sort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MovementTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MovementTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MovementTypesTable::configure($table);
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
            'index' => ListMovementTypes::route('/'),
            'create' => CreateMovementType::route('/create'),
            'view' => ViewMovementType::route('/{record}'),
            'edit' => EditMovementType::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return $query->where('user_id', auth()->id());
    }

    public static function getNavigationGroup(): UnitEnum|string|null
    {
        return __('app.hatsuhi.groups.settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('app.movement_types.title');
    }
}
