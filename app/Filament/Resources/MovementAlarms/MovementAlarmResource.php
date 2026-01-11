<?php

namespace App\Filament\Resources\MovementAlarms;

use App\Filament\Resources\MovementAlarms\Pages\CreateMovementAlarm;
use App\Filament\Resources\MovementAlarms\Pages\EditMovementAlarm;
use App\Filament\Resources\MovementAlarms\Pages\ListMovementAlarms;
use App\Filament\Resources\MovementAlarms\Pages\ViewMovementAlarm;
use App\Filament\Resources\MovementAlarms\Schemas\MovementAlarmForm;
use App\Filament\Resources\MovementAlarms\Schemas\MovementAlarmInfolist;
use App\Filament\Resources\MovementAlarms\Tables\MovementAlarmsTable;
use App\Models\MovementAlarm;
use UnitEnum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;


class MovementAlarmResource extends Resource
{
    protected static ?string $model = MovementAlarm::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static UnitEnum|string|null $navigationGroup = 'Dash Settings';
    protected static ?int $sort = 2;

    protected static ?string $recordTitleAttribute = 'concept';

    public static function form(Schema $schema): Schema
    {
        return MovementAlarmForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MovementAlarmInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MovementAlarmsTable::configure($table);
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
            'index' => ListMovementAlarms::route('/'),
            'create' => CreateMovementAlarm::route('/create'),
            'view' => ViewMovementAlarm::route('/{record}'),
            'edit' => EditMovementAlarm::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        return $query->where('user_id', auth()->id());
    }
}
