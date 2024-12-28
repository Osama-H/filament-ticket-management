<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;


class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\DeleteAction::make(),
            Actions\Action::make('Update Password')
                ->form([
                    TextInput::make('password')->required()->password()->confirmed(),
                    TextInput::make('password_confirmation')->required()->password(),
                ])
                ->action(function (array $data) {

                    $this->record->update([
                        'password' => $data['password'],
                    ]);

                    Notification::make()
                        ->title('password updated successfully')
                        ->success()
                        ->send();
                })
        ];

    }
}
