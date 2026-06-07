<?php

namespace App\Filament\Resources\Movements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use App\Models\MovementCategory;

class MovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('movement_category_id')
                    ->options(function () {
                        return MovementCategory::query()
                            ->where('user_id', auth()->id())
                            ->with('movementType')
                            ->get()
                            ->groupBy(fn ($record) => $record->movementType->name) 
                            ->map(fn ($group) => $group->pluck('name', 'id'))
                            ->toArray();
                    })
                    ->label('Category')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->rules(['min:0.01'])
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ($state) {
                            $set('shared_amount', round($state * ($get('share') / 100), 2) );
                        }
                    }),
                TextInput::make('concept')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Monthly salary, Grocery shopping')
                    ->columnSpan(3),
                TextInput::make('share')
                    ->helperText('Share % (only if the account is shared)')
                    ->required()
                    ->default(function () {
                        $defaultAccount = auth()->user()->accounts()->where('is_main', true)->first();
                        return $defaultAccount ? $defaultAccount->share : 0;
                    })
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $set('shared_amount', round(0 * ($state / 100), 2));
                        }
                    })
                    ->rules(['between:0,100']),
                TextInput::make('shared_amount')
                    ->helperText('Shared Amount (only if the account is shared) (read-only)')
                    ->readOnly()
                    ->default(function () {
                        $defaultAccount = auth()->user()->accounts()->where('is_main', true)->first();
                        return $defaultAccount ? round(0 * ($defaultAccount->share / 100), 2) : 0;
                    })
                    ->numeric(),
                Select::make('account_id')
                    ->options(function () {
                        return auth()->user()->accounts()->pluck('name', 'id');
                    })
                    ->label('Account')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->default(function () {
                        return auth()->user()->accounts()->where('is_main', true)->first()?->id;
                    })
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $account = auth()->user()->accounts()->find($state);
                            $set('share', $account ? $account->share : 0);
                            $set('shared_amount', $account && $account->share ? round(0 * ($account->share / 100), 2) : 0);
                        }
                    }),
                Toggle::make('is_compensation')
                    ->label('Is it a Compensation?')
                    ->helperText('A compensation happens when a refund happens: e.g you have to refund money from a previous Income Movement and you do not want to edit that Movement. It happens also the other way round, a previous Expense is compensated for example the refund of a purchase.')
                    ->default(false)
                    ->required()
                    ->columnSpan(3),
            ])->columns(3);
    }
}
