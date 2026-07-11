<?php

namespace App\Filament\Resources\Movements\Pages;

use App\Filament\Resources\Movements\MovementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMovement extends CreateRecord
{
    protected static string $resource = MovementResource::class;

    public function getTitle(): string
    {
        return __('app.create', ['record' => __('app.movements.single')]);
    }

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
