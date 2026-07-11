<?php

namespace App\Filament\Resources\Accounts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label(__('app.accounts.fields.name')),
                IconColumn::make('is_main')
                    ->boolean()
                    ->sortable()
                    ->label(__('app.accounts.fields.is_main')),
                IconColumn::make('is_shared')
                    ->boolean()
                    ->sortable()
                    ->label(__('app.accounts.fields.is_shared')),
                TextColumn::make('share')
                    ->numeric()
                    ->sortable()
                    ->label(__('app.accounts.fields.share')),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
