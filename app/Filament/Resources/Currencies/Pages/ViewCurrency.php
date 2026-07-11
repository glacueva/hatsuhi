<?php

namespace App\Filament\Resources\Currencies\Pages;

use App\Filament\Resources\Currencies\CurrencyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCurrency extends ViewRecord
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label(__('app.edit', ['record' => __('app.currencies.single')])),
        ];
    }

    public function getTitle(): string
    {
        return __('app.view', ['record' => __('app.currencies.single')]);
    }
}
