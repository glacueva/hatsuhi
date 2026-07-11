<?php

namespace App\Filament\Resources\MovementCategories\Pages;

use App\Filament\Resources\MovementCategories\MovementCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMovementCategory extends CreateRecord
{
    protected static string $resource = MovementCategoryResource::class;

    public function getTitle(): string
    {
        return __('app.create', ['record' => __('app.categories.single')]);
    }
}
