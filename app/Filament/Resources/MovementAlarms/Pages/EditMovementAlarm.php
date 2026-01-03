<?php

namespace App\Filament\Resources\MovementAlarms\Pages;

use App\Filament\Resources\MovementAlarms\MovementAlarmResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMovementAlarm extends EditRecord
{
    protected static string $resource = MovementAlarmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
