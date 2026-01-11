<?php

namespace App\Filament\Resources\Movements\Pages;

use App\Filament\Resources\Movements\MovementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMovement extends CreateRecord
{
    protected static string $resource = MovementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array 
    { 
        // Compensations always comes as a negative number
        $data['amount'] = $data['compensation'] ? -abs($data['amount']) : abs($data['amount']); 
        
        return $data;
    } 

    protected function mutateFormDataBeforeSave(array $data): array 
    {
        $data['amount'] = $data['compensation'] ? -abs($data['amount']) : abs($data['amount']); 

        return $data; 
    }
    protected function mutateFormDataBeforeFill(array $data): array 
    { 
        //always show positive amounts in form
        $data['amount'] = abs($data['amount']); 
        return $data; 
    }
}
