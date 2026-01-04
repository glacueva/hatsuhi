<?php

namespace App\Filament\Widgets;

use App\Models\Movement;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

class RecentMovements extends TableWidget
{
    protected $listeners = ['refreshDashboard' => '$refresh'];
    protected int | string | array $columnSpan = 2;
    protected static ?int $sort = 3;


    public function table(Table $table): Table
    {
        $selectedYear = Session::get('dashboard_year', now()->year);
        $selectedMonth = Session::get('dashboard_month', now()->month);
        
        return $table
            ->query(function () use ($selectedYear, $selectedMonth): Builder {
                return Movement::query()
                    ->whereYear('date', $selectedYear)
                    ->whereMonth('date', $selectedMonth)
                    ->latest()
                    ->limit(30);
            })
            ->heading('Recent Movements - ' . date('F Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)))
            ->columns([
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('concept')
                    ->searchable(),
                TextColumn::make('amount')
                    ->money(fn() => auth()->user()->currency->short ?? 'USD')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}