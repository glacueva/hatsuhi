<?php

namespace App\Filament\Resources\Expectations\Pages;

use App\Filament\Resources\Expectations\ExpectationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewExpectation extends ViewRecord
{
    protected static string $resource = ExpectationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label(__('app.edit', ['record' => __('app.expectations.single')])),
        ];
    }

    public function getTitle(): string
    {
        return __('app.view', ['record' => __('app.expectations.single')]);
    }
}
