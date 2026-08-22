<?php

namespace App\Filament\Resources\HseReportResource\Pages;

use App\Filament\Resources\HseReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHseReport extends CreateRecord
{
    protected static string $resource = HseReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
}
