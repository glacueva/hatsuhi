<?php

namespace App\Filament\Resources\Currencies\Pages;

use App\Filament\Resources\Currencies\CurrencyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCurrency extends EditRecord
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label(__('app.view', ['record' => __('app.currencies.single')])),
            DeleteAction::make()
                ->label(__('app.delete', ['record' => __('app.currencies.single')])),
        ];
    }

    public function getTitle(): string
    {
        return __('app.edit', ['record' => __('app.currencies.single')]);
    }
}
