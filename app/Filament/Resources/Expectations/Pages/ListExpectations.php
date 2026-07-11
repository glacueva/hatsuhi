<?php

namespace App\Filament\Resources\Expectations\Pages;

use App\Filament\Resources\Expectations\ExpectationResource;
use App\Models\Expectation;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;

class ListExpectations extends ListRecords
{
    protected static string $resource = ExpectationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clone_entire_year')
                ->label(__('app.expectations.actions.clone'))
                ->color('gray')
                ->form([
                    Select::make('from_year')
                        ->label(__('app.expectations.labels.source_year'))
                        ->options(fn () => Expectation::distinct()->pluck('year', 'year'))
                        ->required(),
                    TextInput::make('to_year')
                        ->label(__('app.expectations.labels.destination_year'))
                        ->numeric()
                        ->default(now()->year)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $expectations = Expectation::where('year', $data['from_year'])
                        ->where('user_id', auth()->id())
                        ->get();

                    foreach ($expectations as $exp) {
                        $clone = $exp->replicate();
                        $clone->year = $data['to_year'];
                        $clone->save();
                    }
                })
                ->successNotificationTitle(__('app.expectations.notifications.year_cloned_successfully')),

            CreateAction::make()
                ->label(__('app.create', ['record' => __('app.expectations.single')]))
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return __('app.expectations.title');
    }
}
