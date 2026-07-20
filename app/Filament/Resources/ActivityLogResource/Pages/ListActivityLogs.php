<?php

namespace App\Filament\Resources\ActivityLogResource\Pages;

use App\Filament\Resources\ActivityLogResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('clean')
                ->label('Clean Old Logs')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    \Artisan::call('activitylog:clean');
                    Notification::make()
                        ->title('Logs Cleaned')
                        ->success()
                        ->body('Old activity logs have been cleaned up.')
                        ->send();
                }),
        ];
    }
}
