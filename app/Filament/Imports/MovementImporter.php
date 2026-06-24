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
            ImportColumn::make('account_name_string')
                ->label('Account Name')
                ->requiredMapping(),

            ImportColumn::make('type_name_string')
                ->label('Type Name')
                ->requiredMapping(),

            ImportColumn::make('category_name_string')
                ->label('Category Name')
                ->requiredMapping(),

            ImportColumn::make('date')
                ->requiredMapping()
                ->rules(['date']),

            ImportColumn::make('concept')
                ->requiredMapping(),

            ImportColumn::make('amount')
                ->numeric()
                ->requiredMapping(),

            ImportColumn::make('compensation_flag')
                ->label('Compensation (Bool)')
                ->boolean()
                ->requiredMapping(),
        ];
    }

    public function resolveRecord(): ?Movement
    {
        $userId = $this->getImport()->user_id ?? auth()->id();

        $account = Account::firstOrCreate([
            'name' => $this->data['account_name_string'],
            'user_id' => $userId,
        ]);

        $type = MovementType::firstOrCreate([
            'name' => $this->data['type_name_string'],
            'user_id' => $userId,
        ]);

        $category = MovementCategory::firstOrCreate([
            'name' => $this->data['category_name_string'],
            'movement_type_id' => $type->id,
            'user_id' => $userId,
        ]);

        $amount = $this->calculateAmount();

        $record = Movement::firstOrNew([
            'user_id' => $userId,
            'account_id' => $account->id,
            'date' => $this->data['date'],
            'concept' => $this->data['concept'],
            'amount' => $amount,
            'share' => $account->share,
            'shared_amount' => $account->share ? round($amount * ($account->share / 100), 2) : 0,
            'movement_category_id' => $category->id,
        ]);

        return $record;
    }

    protected function calculateAmount(): float
    {
        $amount = abs((float) $this->data['amount']);
        $isCompensation = (bool) $this->data['compensation_flag'];

        return $isCompensation ? -$amount : $amount;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your movements list import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
