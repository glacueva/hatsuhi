<?php

namespace App\Filament\Imports;

use App\Models\Movement;
use App\Models\MovementCategory;
use App\Models\MovementType;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class MovementImporter extends Importer
{
    protected static ?string $model = Movement::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('type_name')
                ->label('Type (Text)')
                ->requiredMapping(),
            
            ImportColumn::make('category_name')
                ->label('Category (Text)')
                ->requiredMapping(),

            ImportColumn::make('date')
                ->requiredMapping()
                ->rules(['date']),

            ImportColumn::make('concept')
                ->requiredMapping(),

            ImportColumn::make('amount')
                ->numeric()
                ->requiredMapping(),
        ];
    }

    protected function beforeSave(): void
    {
        
        $originalAmount = (float) $this->data['amount'];
        $typeName = $this->data['type_name'];
        $categoryName = $this->data['category_name'];
        $user_id = auth()->id();
        
        $type = MovementType::firstOrCreate(
            [
                'name' => $typeName,
                'is_positive' => $originalAmount >= 0 ? 1 : 0,
                'user_id' => $user_id
            ]
        );

        $category = MovementCategory::firstOrCreate(
            [
                'name' => $categoryName,
                'movement_type_id' => $type->id,
                'user_id' => $user_id
            ]
        );

        $this->record->movement_category_id = $category->id;
        $this->record->user_id = $user_id;
        $this->record->amount = abs($originalAmount);
    }

    public function resolveRecord(): Movement
    {
        return new Movement();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your movement import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
