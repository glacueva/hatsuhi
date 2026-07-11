<?php

namespace App\Filament\Resources\MovementCategories\Pages;

use App\Filament\Resources\MovementCategories\MovementCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMovementCategory extends ViewRecord
{
    protected static string $resource = MovementCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label(__('app.edit', ['record' => __('app.categories.single')])),
        ];
    }

    public function getTitle(): string
    {
        return __('app.view', ['record' => __('app.categories.single')]);
    }
}
