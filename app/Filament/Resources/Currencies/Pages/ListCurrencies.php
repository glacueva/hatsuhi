<?php

namespace App\Filament\Resources\Currencies\Pages;

use App\Filament\Resources\Currencies\CurrencyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCurrencies extends ListRecords
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('app.create', ['record' => __('app.currencies.single')]))
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return __('app.currencies.title');
    }
}
