<?php

namespace App\Filament\Resources\MovementCategories\Pages;

use App\Filament\Resources\MovementCategories\MovementCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMovementCategories extends ListRecords
{
    protected static string $resource = MovementCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('app.create', ['record' => __('app.categories.single')]))
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return __('app.categories.title');
    }
}
