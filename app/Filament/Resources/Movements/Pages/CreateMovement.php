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
        return MovementResource::compensateMovement($data);
    } 

    protected function mutateFormDataBeforeSave(array $data): array 
    {
        return MovementResource::compensateMovement($data);
    }
}
