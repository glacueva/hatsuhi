<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label(__('app.edit', ['record' => __('app.users.single')])),
        ];
    }

    public function getTitle(): string
    {
        return __('app.view', ['record' => __('app.users.single')]);
    }
}
