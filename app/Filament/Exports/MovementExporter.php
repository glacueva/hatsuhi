<?php

namespace App\Filament\Exports;

use App\Models\Movement;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class MovementExporter extends Exporter
{
    protected static ?string $model = Movement::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('type_name_string')->label('type_name')->state(
                function (Movement $record) {
                    return $record->category->movementType->name;
                }),
            ExportColumn::make('category_name_string')->label('category_name')->state(
                function (Movement $record) {
                    return $record->category->name;
                }),
            ExportColumn::make('date')->label('date'),
            ExportColumn::make('concept')->label('concept'),
            ExportColumn::make('amount')->label('amount')->state(
                function (Movement $record) {
                    return $record->absolute_amount;
                }),
            ExportColumn::make('compensation')->label('compensation')->state(
                function (Movement $record) {
                    return $record->compensation;
                })
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your movement export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
