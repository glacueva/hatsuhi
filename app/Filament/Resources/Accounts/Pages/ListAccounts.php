<?php

namespace App\Filament\Resources\Accounts\Pages;

use App\Filament\Resources\Accounts\AccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAccounts extends ListRecords
{
    protected static string $resource = AccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label(__('app.create', ['record' => __('app.accounts.single')]))
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return __('app.accounts.title');
    }
}
