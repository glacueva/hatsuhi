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
            ViewAction::make()
                ->label(__('app.view', ['record' => __('app.movement_types.single')])),
            DeleteAction::make()
                ->label(__('app.delete', ['record' => __('app.movement_types.single')])),
        ];
    }

    public function getTitle(): string
    {
        return __('app.edit', ['record' => __('app.movement_types.single')]);
    }
}
