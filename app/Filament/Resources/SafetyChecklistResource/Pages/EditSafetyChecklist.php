<?php

namespace App\Filament\Resources\SafetyChecklistResource\Pages;

use App\Filament\Resources\SafetyChecklistResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSafetyChecklist extends EditRecord
{
    protected static string $resource = SafetyChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
