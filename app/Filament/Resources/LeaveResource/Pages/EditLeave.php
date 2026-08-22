<?php

namespace App\Filament\Resources\LeaveResource\Pages;

use App\Enums\ApprovalStatus;
use App\Filament\Resources\LeaveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeave extends EditRecord
{
    protected static string $resource = LeaveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Setujui')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status === ApprovalStatus::Pending && auth()->user()->can('update_leave'))
                ->action(function (): void {
                    $this->record->update([
                        'status' => ApprovalStatus::Approved,
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                    $this->refreshFormData(['status', 'approved_at']);
                }),
            Actions\Action::make('reject')
                ->label('Tolak')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status === ApprovalStatus::Pending && auth()->user()->can('update_leave'))
                ->action(function (): void {
                    $this->record->update([
                        'status' => ApprovalStatus::Rejected,
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);

                    $this->refreshFormData(['status', 'approved_at']);
                }),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
