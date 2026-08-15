<?php

namespace App\Filament\Resources\MailArchiveResource\Pages;

use App\Filament\Resources\MailArchiveResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMailArchive extends CreateRecord
{
    protected static string $resource = MailArchiveResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['uploaded_by'] = auth()->id();

        return $data;
    }
}
