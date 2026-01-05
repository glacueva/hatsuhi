<?php

namespace App\Filament\Resources\Movements\Pages;

use App\Filament\Resources\Movements\MovementResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMovement extends EditRecord
{
    protected static string $resource = MovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array 
    {
        $data['amount'] = $data['compensation'] ? -abs($data['amount']) : abs($data['amount']); 

        return $data; 
    }
    protected function mutateFormDataBeforeFill(array $data): array 
    { 
        //always show positive amounts in form
        $data['compensation'] = $data['amount'] < 0;
        $data['amount'] = abs($data['amount']); 
        return $data; 
    }
}
