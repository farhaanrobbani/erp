<?php

namespace App\Filament\Resources\SafetyChecklistResource\Pages;

use App\Filament\Resources\SafetyChecklistResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSafetyChecklist extends CreateRecord
{
    protected static string $resource = SafetyChecklistResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['checked_by'] = auth()->id();

        return $data;
    }
}
