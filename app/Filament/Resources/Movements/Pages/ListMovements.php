<?php

namespace App\Filament\Resources\Movements\Pages;

use App\Filament\Resources\Movements\MovementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMovements extends ListRecords
{
    protected static string $resource = MovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('app.create', ['record' => __('app.movements.single')]))
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return __('app.movements.title');
    }
}
