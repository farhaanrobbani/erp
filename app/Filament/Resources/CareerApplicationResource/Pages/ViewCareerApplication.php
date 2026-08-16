<?php

namespace App\Filament\Resources\CareerApplicationResource\Pages;

use App\Filament\Resources\CareerApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Storage;

class ViewCareerApplication extends ViewRecord
{
    protected static string $resource = CareerApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('download_resume')
                ->label('Unduh CV')
                ->icon('heroicon-m-arrow-down-tray')
                ->color('gray')
                ->visible(fn () => filled($this->record->resume_path))
                ->url(fn () => Storage::disk('public')->url($this->record->resume_path))
                ->openUrlInNewTab(),
        ];
    }
}
