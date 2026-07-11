<?php

namespace App\Filament\Resources\Expectations\Pages;

use App\Filament\Resources\Expectations\ExpectationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExpectation extends CreateRecord
{
    protected static string $resource = ExpectationResource::class;

    public function getTitle(): string
    {
        return __('app.create', ['record' => __('app.expectations.single')]);
    }
}
