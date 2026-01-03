<?php

namespace App\Filament\Resources\MovementAlarms\Pages;

use App\Filament\Resources\MovementAlarms\MovementAlarmResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMovementAlarms extends ListRecords
{
    protected static string $resource = MovementAlarmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
