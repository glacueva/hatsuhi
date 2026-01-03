<?php

namespace App\Filament\Resources\MovementAlarms\Pages;

use App\Filament\Resources\MovementAlarms\MovementAlarmResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMovementAlarm extends ViewRecord
{
    protected static string $resource = MovementAlarmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
