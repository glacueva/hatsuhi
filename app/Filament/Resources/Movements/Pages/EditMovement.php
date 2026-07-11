<?php

namespace App\Filament\Resources\Movements\Pages;

use App\Filament\Resources\Movements\MovementResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMovement extends EditRecord
{
    protected static string $resource = MovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label(__('app.view', ['record' => __('app.movements.single')])),
            DeleteAction::make()
                ->label(__('app.delete', ['record' => __('app.movements.single')])),
        ];
    }

    public function getTitle(): string
    {
        return __('app.edit', ['record' => __('app.movements.single')]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return MovementResource::compensateMovement($data);
    }
}
