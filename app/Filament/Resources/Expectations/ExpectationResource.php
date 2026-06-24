<?php

namespace App\Filament\Resources\Expectations;

use App\Filament\Resources\Expectations\Pages\CreateExpectation;
use App\Filament\Resources\Expectations\Pages\EditExpectation;
use App\Filament\Resources\Expectations\Pages\ListExpectations;
use App\Filament\Resources\Expectations\Pages\ViewExpectation;
use App\Filament\Resources\Expectations\Schemas\ExpectationForm;
use App\Filament\Resources\Expectations\Schemas\ExpectationInfolist;
use App\Filament\Resources\Expectations\Tables\ExpectationsTable;
use App\Models\Expectation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ExpectationResource extends Resource
{
    protected static ?string $model = Expectation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static UnitEnum|string|null $navigationGroup = 'Dash Settings';

    protected static ?int $sort = 2;

    public static function form(Schema $schema): Schema
    {
        return ExpectationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ExpectationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpectationsTable::configure($table);
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
            'index' => ListExpectations::route('/'),
            'create' => CreateExpectation::route('/create'),
            'view' => ViewExpectation::route('/{record}'),
            'edit' => EditExpectation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return $query->where('user_id', auth()->id());
    }
}
