<?php

namespace App\Filament\Resources\MovementTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MovementTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                IconColumn::make('is_positive')
                    ->boolean()
                    ->label('Type')
                    ->getStateUsing(function ($record) {
                        return $record->is_positive ? 'Income' : 'Expense';
                    })
                    ->icon(function ($state) {
                        return $state === 'Income' ? 'heroicon-o-arrow-up' : 'heroicon-o-arrow-down';
                    })
                    ->color(function ($state) {
                        return $state === 'Income' ? 'success' : 'danger';
                    }),
                TextColumn::make('categories_count')
                    ->counts('categories')
                    ->label('Categories')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_positive')
                    ->options([
                        '1' => 'Income',
                        '0' => 'Expense',
                    ])
                    ->label('Type'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->before(function ($record): void {
                        // Prevent deletion if there are categories
                        if ($record->categories()->count() > 0) {
                            throw new \Exception('Cannot delete movement type with existing categories.');
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([

                ]),
            ]);
    }
}
