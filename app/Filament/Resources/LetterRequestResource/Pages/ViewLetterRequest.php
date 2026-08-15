<?php

namespace App\Filament\Resources\LetterRequestResource\Pages;

use App\Filament\Resources\LetterRequestResource;
use App\Models\LetterRequest;
use App\Services\LetterApprovalService;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Exceptions\Halt;

class ViewLetterRequest extends ViewRecord implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = LetterRequestResource::class;

    public function getHeaderActions(): array
    {
        $record = $this->getRecord();

        return [
            Actions\Action::make('approve')
                ->label('Setujui & Terbitkan Nomor')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $record->status->value === 'pending'
                    && auth()->user()?->can('approve_letter-request'))
                ->requiresConfirmation()
                ->modalHeading('Setujui pengajuan nomor surat?')
                ->modalDescription('Nomor surat akan dibuat otomatis mengikuti format kategori yang dipilih.')
                ->action(function () use ($record) {
                    try {
                        app(LetterApprovalService::class)->approve($record, auth()->user());

                        Notification::make()
                            ->title('Pengajuan disetujui')
                            ->body('Nomor surat berhasil diterbitkan.')
                            ->success()
                            ->send();

                        $this->redirect(static::getResource()::getUrl('index'));
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Gagal menyetujui pengajuan')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();

                        throw new Halt($e->getMessage());
                    }
                }),

            Actions\Action::make('reject')
                ->label('Tolak')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => $record->status->value === 'pending'
                    && auth()->user()?->can('reject_letter-request'))
                ->form([
                    Textarea::make('rejection_reason')
                        ->label('Alasan Penolakan')
                        ->required()
                        ->maxLength(255),
                ])
                ->action(function (array $data) use ($record) {
                    app(LetterApprovalService::class)->reject($record, auth()->user(), $data['rejection_reason']);

                    Notification::make()
                        ->title('Pengajuan ditolak')
                        ->danger()
                        ->send();

                    $this->redirect(static::getResource()::getUrl('index'));
                }),

            Actions\Action::make('upload_final')
                ->label('Unggah Dokumen Final')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('info')
                ->visible(fn () => $record->status->value === 'approved')
                ->form([
                    FileUpload::make('file_path')
                        ->label('File PDF Surat (Sudah Ditandatangani)')
                        ->disk('public')
                        ->directory('letters')
                        ->acceptedFileTypes(['application/pdf'])
                        ->required(),
                ])
                ->action(function (array $data, LetterRequest $record) {
                    $record->update(['file_path' => $data['file_path']]);

                    Notification::make()
                        ->title('Dokumen final berhasil diarsipkan')
                        ->success()
                        ->send();
                }),

            Actions\EditAction::make()
                ->visible(fn () => $record->status->value === 'pending'
                    && auth()->user()?->can('update_letter-request')),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
