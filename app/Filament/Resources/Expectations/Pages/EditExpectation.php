<?php

namespace App\Filament\Resources\Expectations\Pages;

use App\Filament\Resources\Expectations\ExpectationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditExpectation extends EditRecord
{
    protected static string $resource = ExpectationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
