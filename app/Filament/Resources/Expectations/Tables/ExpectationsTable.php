<?php

namespace App\Filament\Resources\Expectations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Expectation;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;


class ExpectationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('year')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('amount')
                    ->state(function ($record) {
                        $symbol = $record->user->currency->symbol ?? '$';
                        return $symbol . $record->amount;
                    })
                    ->sortable()
                    ->label('Yearly Amount'),
                TextColumn::make('monthly_amount')
                    ->label('Monthly')
                    ->state(function ($record) {
                        $symbol = $record->user->currency->symbol ?? '$';
                        return $symbol . number_format($record->amount / 12, 2);
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
                    ->options(function () {
                        $years = Expectation::query()->where('user_id', auth()->id())
                            ->distinct('year')
                            ->pluck('year')
                            ->mapWithKeys(fn($year) => [$year => $year])
                            ->toArray();
                        
                        return $years;
                    })
            ])
            ->groups([
                Group::make('year')
                    ->label('Year')
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
                    DeleteBulkAction::make()
                ])
            ]);
    }
}
