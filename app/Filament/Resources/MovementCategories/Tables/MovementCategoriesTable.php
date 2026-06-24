<?php

namespace App\Filament\Resources\MovementCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MovementCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('movementType.name')
                    ->label('Type')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state === 'Income' ? 'primary' : 'danger'),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('movements_count')
                    ->counts('movements')
                    ->label('Transactions')
                    ->sortable(),
                TextColumn::make('expectations_count')
                    ->counts('expectations')
                    ->label('Budgets')
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
                SelectFilter::make('movement_type_id')
                    ->relationship('movementType', 'name')
                    ->label('Movement Type')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->before(function ($record) {
                        // Prevent deletion if there are movements
                        $movCount = $record->movements()->count() > 0;
                        $expCount = $record->expectations()->count() > 0;

                        if ($movCount || $expCount > 0) {
                            throw new \Exception('Cannot delete movement category with existing movements or expectations.');
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([

                ]),
            ]);
    }
}
