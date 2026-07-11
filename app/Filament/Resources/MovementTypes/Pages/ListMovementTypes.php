<?php

namespace App\Filament\Resources\MovementTypes\Pages;

use App\Filament\Resources\MovementTypes\MovementTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMovementTypes extends ListRecords
{
    protected static string $resource = MovementTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('app.create', ['record' => __('app.movement_types.single')]))
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return __('app.movement_types.title');
    }
}
