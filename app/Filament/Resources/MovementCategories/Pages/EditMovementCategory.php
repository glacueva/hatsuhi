<?php

namespace App\Filament\Resources\MovementCategories\Pages;

use App\Filament\Resources\MovementCategories\MovementCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMovementCategory extends EditRecord
{
    protected static string $resource = MovementCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label(__('app.view', ['record' => __('app.categories.single')])),
            DeleteAction::make()
                ->label(__('app.delete', ['record' => __('app.categories.single')])),
        ];
    }

    public function getTitle(): string
    {
        return __('app.edit', ['record' => __('app.categories.single')]);
    }
}
