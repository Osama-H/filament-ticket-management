<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    public function getCreatedNotification(): ?Notification
    {
        return Notification::make()->success()
            ->title('Category created')
            ->body('Category created Successfully!');
    }


}
