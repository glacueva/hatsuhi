<?php

namespace App\Filament\Resources\Movements\Schemas;

use App\Models\MovementCategory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('movement_category_id')
                    ->label(__('app.movements.fields.category'))
                    ->options(function () {
                        return MovementCategory::query()
                            ->where('user_id', auth()->id())
                            ->with('movementType')
                            ->get()
                            ->groupBy(fn ($record) => $record->movementType->name)
                            ->map(fn ($group) => $group->pluck('name', 'id'))
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('date')
                    ->label(__('app.movements.fields.date'))
                    ->required(),
                TextInput::make('amount')
                    ->label(__('app.movements.fields.amount'))
                    ->required()
                    ->numeric()
                    ->rules(['min:0.01'])
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, callable $get): void {
                        if ($state) {
                            $set('shared_amount', round($state * ($get('share') / 100), 2));
                        }
                    }),
                TextInput::make('concept')
                    ->label(__('app.movements.fields.concept'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('app.movements.fields.concept_placeholder'))
                    ->columnSpan(3),
                TextInput::make('share')
                    ->label(__('app.movements.fields.share'))
                    ->helperText(__('app.movements.fields.share_helper'))
                    ->required()
                    ->default(function () {
                        $defaultAccount = auth()->user()->accounts()->where('is_main', true)->first();

                        return $defaultAccount ? $defaultAccount->share : 0;
                    })
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set): void {
                        if ($state) {
                            $set('shared_amount', round(0 * ($state / 100), 2));
                        }
                    })
                    ->rules(['between:0,100']),
                TextInput::make('shared_amount')
                    ->label(__('app.movements.fields.shared_amount'))
                    ->helperText(__('app.movements.fields.shared_amount_helper'))
                    ->readOnly()
                    ->default(function () {
                        $defaultAccount = auth()->user()->accounts()->where('is_main', true)->first();

                        return $defaultAccount ? round(0 * ($defaultAccount->share / 100), 2) : 0;
                    })
                    ->numeric(),
                Select::make('account_id')
                    ->label(__('app.movements.fields.account'))
                    ->options(function () {
                        return auth()->user()->accounts()->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->default(function () {
                        return auth()->user()->accounts()->where('is_main', true)->first()?->id;
                    })
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set): void {
                        if ($state) {
                            $account = auth()->user()->accounts()->find($state);
                            $set('share', $account ? $account->share : 0);
                            $set('shared_amount', $account && $account->share ? round(0 * ($account->share / 100), 2) : 0);
                        }
                    }),
                Toggle::make('is_compensation')
                    ->label(__('app.movements.fields.is_compensation'))
                    ->helperText(__('app.movements.fields.is_compensation_helper'))
                    ->default(false)
                    ->required()
                    ->columnSpan(3),
            ])->columns(3);
    }
}
