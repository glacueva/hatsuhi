<?php

namespace App\Filament\Resources\Movements\Pages;

use App\Filament\Resources\Movements\MovementResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMovement extends ViewRecord
{
    protected static string $resource = MovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function mutateFormDataBeforeView(array $data): array 
    { 
        //always show positive amounts in form
        $data['amount'] = abs($data['amount']); 
        $data['shared_amount'] = abs($data['shared_amount']);

        return $data; 
    }
}
