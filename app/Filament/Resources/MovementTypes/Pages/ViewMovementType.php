<?php

namespace App\Filament\Resources\MovementTypes\Pages;

use App\Filament\Resources\MovementTypes\MovementTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMovementType extends ViewRecord
{
    protected static string $resource = MovementTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label(__('app.edit', ['record' => __('app.movement_types.single')])),
        ];
    }

    public function getTitle(): string
    {
        return __('app.view', ['record' => __('app.movement_types.single')]);
    }
}
