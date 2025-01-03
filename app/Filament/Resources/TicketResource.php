<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required()->autofocus(),
                Forms\Components\Textarea::make('description')->required()->rows(3),
                Forms\Components\Select::make('status')->options(self::$model::STATUS)->required()->in(self::$model::STATUS),
                Forms\Components\Select::make('priority')->options(self::$model::PRIORITY)->required()->in(self::$model::PRIORITY),
                Forms\Components\Textarea::make('comment')->required()->rows(3),
                Forms\Components\Select::make('assigned_to')
                    ->options(User::whereHas('roles', function (Builder $query) {
                        $query->where('name', Role::ROLES['Agent']);
                    })->pluck('name', 'id')->toArray())
                    ->required(),
                Forms\Components\FileUpload::make('attachment')


            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => auth()->user()->hasRole(Role::ROLES['Admin'])
                ? $query : $query->where('assigned_to', auth()->id()))
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->description(fn(Ticket $record): ?string => $record?->description ?? null)
                    ->searchable()
                    ->sortable(),


                Tables\Columns\SelectColumn::make('status')->options(self::$model::STATUS)->searchable()->sortable(),


//                Tables\Columns\TextColumn::make('status')
//                    ->badge()
//                    ->colors([
//                        'warning' => self::$model::STATUS['Open'],
//                        'success' => self::$model::STATUS['Closed'],
//                        'danger' => self::$model::STATUS['Archived'],
//                    ]),

                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->colors([
                        'warning' => self::$model::PRIORITY['Medium'],
                        'success' => self::$model::PRIORITY['Low'],
                        'danger' => self::$model::PRIORITY['High'],
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
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(self::$model::STATUS),
                SelectFilter::make('priority')
                    ->options(self::$model::PRIORITY),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CategoriesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
