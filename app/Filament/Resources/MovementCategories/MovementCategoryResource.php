<?php

namespace App\Filament\Resources\MovementCategories;

use App\Filament\Resources\MovementCategories\Pages\CreateMovementCategory;
use App\Filament\Resources\MovementCategories\Pages\EditMovementCategory;
use App\Filament\Resources\MovementCategories\Pages\ListMovementCategories;
use App\Filament\Resources\MovementCategories\Pages\ViewMovementCategory;
use App\Filament\Resources\MovementCategories\Schemas\MovementCategoryForm;
use App\Filament\Resources\MovementCategories\Schemas\MovementCategoryInfolist;
use App\Filament\Resources\MovementCategories\Tables\MovementCategoriesTable;
use App\Models\MovementCategory;
use UnitEnum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;

class MovementCategoryResource extends Resource
{
    protected static ?string $model = MovementCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static UnitEnum|string|null $navigationGroup = 'Settings';
    protected static ?int $sort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MovementCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MovementCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MovementCategoriesTable::configure($table);
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
            'index' => ListMovementCategories::route('/'),
            'create' => CreateMovementCategory::route('/create'),
            'view' => ViewMovementCategory::route('/{record}'),
            'edit' => EditMovementCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        return $query->where('user_id', auth()->id());
    }
}
