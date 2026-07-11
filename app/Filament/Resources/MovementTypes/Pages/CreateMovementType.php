<?php

namespace App\Filament\Resources\MovementTypes\Pages;

use App\Filament\Resources\MovementTypes\MovementTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMovementType extends CreateRecord
{
    protected static string $resource = MovementTypeResource::class;

    public function getTitle(): string
    {
        return __('app.create', ['record' => __('app.movement_types.single')]);
    }
}
