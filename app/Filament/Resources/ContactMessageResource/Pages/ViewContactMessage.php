<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('mark_read')
                ->label('Tandai Dibaca')
                ->icon('heroicon-m-check')
                ->color('success')
                ->visible(fn () => ! $this->record->is_read)
                ->action(function (ContactMessage $record): void {
                    $record->update(['is_read' => true, 'responded_at' => now()]);
                    $this->redirect($this->getUrl());
                }),
        ];
    }
}
