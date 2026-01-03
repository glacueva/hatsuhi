<?php

namespace App\Filament\Resources\MovementTypes\Pages;

use App\Filament\Resources\MovementTypes\MovementTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMovementType extends EditRecord
{
    protected static string $resource = MovementTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
