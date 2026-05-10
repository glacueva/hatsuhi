<?php

namespace App\Filament\Resources\Movements\Pages;

use App\Filament\Resources\Movements\MovementResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMovement extends EditRecord
{
    protected static string $resource = MovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array 
    {
        $data['amount'] = $data['compensation'] ? -abs($data['amount']) : abs($data['amount']); 

        $data['share'] = $this->getSharePercentage($data['account_id'], $data['share']);
        $data['shared_amount'] = $data['account_id'] && $data['share'] ? round($data['amount'] * ($data['share'] / 100), 2) : 0;

        return $data; 
    }
    protected function mutateFormDataBeforeFill(array $data): array 
    { 
        //always show positive amounts in form
        $data['compensation'] = $data['amount'] < 0;
        $data['amount'] = abs($data['amount']); 

        $data['share'] = $this->getSharePercentage($data['account_id'], $data['share']);
        $data['shared_amount'] = $data['account_id'] && $data['share'] ? round($data['amount'] * ($data['share'] / 100), 2) : 0;
        return $data; 
    }

    public function getSharePercentage(int $accountId, float $share): float
    {
        $account = auth()->user()->shared_accounts()->find($accountId);

        if(!$account) {
            return 100;
        }
        
        return $account ? $share : $account->share;
    }
}
