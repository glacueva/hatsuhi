<?php

namespace App\Filament\Resources\Movements\Tables;

use App\Filament\Exports\MovementExporter;
use App\Filament\Imports\MovementImporter;
use App\Filament\Resources\Movements\MovementResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('positive_flow')
                    ->label('Flow')
                    ->icon(
                        fn ($state) => $state ? Heroicon::ArrowTrendingUp : Heroicon::ArrowTrendingDown
                    )
                    ->color(fn (bool $state): string => match ($state) {
                        true => 'success',
                        false => 'info',
                    })
                    ->toggleable(true),
                TextColumn::make('account.name')
                    ->label('Account')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('category.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date')
                    ->sortable()
                    ->date(),
                TextColumn::make('concept')
                    ->searchable(),
                TextColumn::make('amount')
                    ->money(fn ($record) => $record?->currency_short ?? 'EUR')
                    ->sortable()
                    ->summarize(
                        Sum::make('amount')
                            ->hiddenLabel()
                            ->money(auth()->user()->currency->short)
                    ),
                TextColumn::make('shared_amount')
                    ->label('Share')
                    ->money(fn ($record) => $record?->currency_short ?? 'EUR')
                    ->sortable()
                    ->summarize(
                        Sum::make('shared_amount')
                            ->hiddenLabel()
                            ->money(auth()->user()->currency->short)
                    ),
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
                SelectFilter::make('account_id')
                    ->relationship('account', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Account'),
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
                    }),

            ])
            ->recordActions([
                ViewAction::make()->iconButton(),
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        return MovementResource::compensateMovement($data);
                    })
                    ->iconButton(),
                DeleteAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    /*
                    * COMMENTED OUT FOR NOW, AS IT'S NOT NEEDED AT THE MOMENT
                    ExportBulkAction::make()
                        ->exporter(MovementExporter::class) // La clase que creamos antes
                        ->label('Export CSV')
                        ->icon('heroicon-o-arrow-down-circle')
                        ->color('info'),
                    */
                ]),
            ])
            ->headerActions([
                /*
                * COMMENTED OUT FOR NOW, AS IT'S NOT NEEDED AT THE MOMENT
                ImportAction::make()
                    ->importer(MovementImporter::class) // La clase que creamos antes
                    ->label('Import CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('warning'),
                */

            ])
            ->defaultSort('date', 'desc');
    }
}
