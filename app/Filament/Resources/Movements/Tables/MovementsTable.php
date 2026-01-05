<?php

namespace App\Filament\Resources\Movements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

use Filament\Forms\Components\DatePicker;

use Illuminate\Database\Eloquent\Builder;

use App\Filament\Imports\MovementImporter;
use App\Filament\Exports\MovementExporter;

class MovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('concept')
                    ->searchable(),
                TextColumn::make('amount')
                    ->state(function ($record) {
                        $symbol = $record->user->currency->symbol ?? '$';
                        return $symbol . $record->absolute_amount;
                    })
                    ->sortable(),
                BadgeColumn::make('compensation')
                    ->label('Compensation')
                    ->colors([ 
                        'danger' => fn ($state) => $state === true, 
                        'gray' => fn ($state) => $state === false, 
                    ])->icons([
                        'heroicon-o-arrow-uturn-left' => fn ($state) => $state === true,
                        'heroicon-o-check' => fn ($state) => $state === false, 
                    ])->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
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
                SelectFilter::make('movement_category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Category'),
                Filter::make('date')
                    ->form([
                        DatePicker::make('date_from'),
                        DatePicker::make('date_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when($data['date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })

            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()
                    ->exporter(MovementExporter::class) // La clase que creamos antes
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-circle')
                    ->color('info')
                ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(MovementImporter::class) // La clase que creamos antes
                    ->label('Import CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('warning'),
                
            ]);
    }
}
