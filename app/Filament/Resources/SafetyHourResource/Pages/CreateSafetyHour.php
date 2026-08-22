<?php

namespace App\Filament\Resources\SafetyHourResource\Pages;

use App\Filament\Resources\SafetyHourResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSafetyHour extends CreateRecord
{
    protected static string $resource = SafetyHourResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['recorded_by'] = auth()->id();

        return $data;
    }
}
