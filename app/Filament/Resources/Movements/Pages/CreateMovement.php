<?php

namespace App\Filament\Resources\Movements\Pages;

use App\Filament\Resources\Movements\MovementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMovement extends CreateRecord
{
    protected static string $resource = MovementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array 
    { 
        // Compensations always comes as a negative number
        $data['amount'] = $data['compensation'] ? -abs($data['amount']) : abs($data['amount']); 

        $data['share'] = $this->getSharePercentage($data['account_id'], $data['share']);
        $data['shared_amount'] = $data['account_id'] && $data['share'] ? round($data['amount'] * ($data['share'] / 100), 2) : 0;
        $data['shared_amount'] = abs($data['shared_amount']);
        
        return $data;
    } 

    protected function mutateFormDataBeforeSave(array $data): array 
    {
        $data['amount'] = $data['compensation'] ? -abs($data['amount']) : abs($data['amount']); 

        $data['share'] = $this->getSharePercentage($data['account_id'], $data['share']);
        $data['shared_amount'] = $data['account_id'] && $data['share'] ? round($data['amount'] * ($data['share'] / 100), 2) : 0;
        $data['shared_amount'] = abs($data['shared_amount']);

        return $data; 
    }
    protected function mutateFormDataBeforeFill(array $data): array 
    { 
        //always show positive amounts in form
        $data['amount'] = abs($data['amount']); 

        $data['share'] = $this->getSharePercentage($data['account_id'], $data['share']);
        $data['shared_amount'] = $data['account_id'] && $data['share'] ? round($data['amount'] * ($data['share'] / 100), 2) : 0;
        $data['shared_amount'] = abs($data['shared_amount']);

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
