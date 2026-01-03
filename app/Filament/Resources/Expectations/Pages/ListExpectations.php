<?php

namespace App\Filament\Resources\Expectations\Pages;

use App\Filament\Resources\Expectations\ExpectationResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\Expectation;


class ListExpectations extends ListRecords
{
    protected static string $resource = ExpectationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clone_entire_year')
                ->label('Duplicate Year Expectations')
                ->color('gray')
                ->form([
                    Select::make('from_year')
                        ->label('Source Year')
                        ->options(fn() => Expectation::distinct()->pluck('year', 'year'))
                        ->required(),
                    TextInput::make('to_year')
                        ->label('Destination Year')
                        ->numeric()
                        ->default(now()->year)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $expectations = Expectation::where('year', $data['from_year'])
                        ->where('user_id', auth()->id())
                        ->get();

                    foreach ($expectations as $exp) {
                        $clone = $exp->replicate();
                        $clone->year = $data['to_year'];
                        $clone->save();
                    }
                })
                ->successNotificationTitle('Year duplicated successfully!'),
                
            CreateAction::make(),
        ];
    }
}
