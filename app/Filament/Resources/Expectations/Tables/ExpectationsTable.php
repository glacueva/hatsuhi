<?php

namespace App\Filament\Resources\Expectations\Tables;

use App\Models\Expectation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class ExpectationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->label(__('app.expectations.fields.movement_category'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('year')
                    ->label(__('app.hatsuhi.year'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('amount')
                    ->state(function ($record) {
                        $symbol = $record->user->currency->symbol ?? '$';

                        return $symbol.$record->amount;
                    })
                    ->sortable()
                    ->label(__('app.expectations.fields.amount')),
                TextColumn::make('monthly_amount')
                    ->label(__('app.expectations.fields.monthly_amount'))
                    ->state(function ($record) {
                        $symbol = $record->user->currency->symbol ?? '$';

                        return $symbol.number_format($record->amount / 12, 2);
                    }),
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
                SelectFilter::make('year')
                    ->label(__('app.hatsuhi.year'))
                    ->options(function () {
                        $years = Expectation::query()->where('user_id', auth()->id())
                            ->distinct('year')
                            ->pluck('year')
                            ->mapWithKeys(fn ($year) => [$year => $year])
                            ->toArray();

                        return $years;
                    }),
            ])
            ->groups([
                Group::make('year')
                    ->label(__('app.hatsuhi.year'))
                    ->collapsible(),
            ])
            ->defaultGroup('year')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
