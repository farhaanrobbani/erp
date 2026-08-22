<?php

namespace App\Filament\Resources\IncidentLogResource\Pages;

use App\Filament\Resources\IncidentLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIncidentLog extends EditRecord
{
    protected static string $resource = IncidentLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('close')
                ->label('Tutup Insiden')
                ->icon('heroicon-o-lock-closed')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status !== 'closed' && auth()->user()->can('update_incident-log'))
                ->action(function (): void {
                    $this->record->update(['status' => 'closed']);
                    $this->refreshFormData(['status']);
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
