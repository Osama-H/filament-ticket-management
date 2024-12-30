<?php

namespace App\Filament\Widgets;

use App\Models\Role;
use App\Models\Ticket;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTickets extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3 ;

    public function table(Table $table): Table
    {
        return $table
            ->query(
            // ...
//                dd(auth()->user()->hasRole(Role::ROLES['Admin']))
                auth()->user()->hasRole(Role::ROLES['Admin']) ? Ticket::query() : Ticket::where('assigned_to', auth()->id())

//                Ticket::query()
            )
            ->columns([
                // ...
                Tables\Columns\TextColumn::make('title')
                    ->description(fn(Ticket $record): ?string => $record?->description ?? null)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => Ticket::STATUS['Open'],
                        'success' => Ticket::STATUS['Closed'],
                        'danger' => Ticket::STATUS['Archived'],
                    ]),

                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->colors([
                        'warning' => Ticket::PRIORITY['Medium'],
                        'success' => Ticket::PRIORITY['Low'],
                        'danger' => Ticket::PRIORITY['High'],
                    ]),
                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedBy.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextInputColumn::make('comment'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}
