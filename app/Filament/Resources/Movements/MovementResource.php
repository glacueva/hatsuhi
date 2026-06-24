<?php

namespace App\Filament\Resources\Movements;

use App\Filament\Resources\Movements\Pages\CreateMovement;
use App\Filament\Resources\Movements\Pages\ListMovements;
use App\Filament\Resources\Movements\Schemas\MovementForm;
use App\Filament\Resources\Movements\Schemas\MovementInfolist;
use App\Filament\Resources\Movements\Tables\MovementsTable;
use App\Models\Movement;
use App\Models\Views\FlowMovementsView;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $query->addSelect([
            'positive_flow' => FlowMovementsView::select('positive_flow')
                ->whereColumn('id', 'movements.id')
                ->limit(1),
            'currency_short' => DB::raw("'".auth()->user()->currency->short."'"),
        ]);

        return $query->where('user_id', auth()->id());
    }

    public static function compensateMovement(array $data): array
    {
        // Get absolute value of amount first

        // Get validated share percentage from account
        $data['share'] = auth()->user()->getSharePercentage($data['account_id'], $data['share']) ?? 100;

        // Calculate shared_amount based on the absolute amount first
        $data['shared_amount'] = round($data['amount'] * ($data['share'] / 100), 2);

        return $data;
    }
}
