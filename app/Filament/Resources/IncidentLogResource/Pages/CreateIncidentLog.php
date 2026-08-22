<?php

namespace App\Filament\Resources\IncidentLogResource\Pages;

use App\Filament\Resources\IncidentLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIncidentLog extends CreateRecord
{
    protected static string $resource = IncidentLogResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['reported_by'] = auth()->id();

        return $data;
    }
}
