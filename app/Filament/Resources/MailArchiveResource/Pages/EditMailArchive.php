<?php

namespace App\Filament\Resources\MailArchiveResource\Pages;

use App\Filament\Resources\MailArchiveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMailArchive extends EditRecord
{
    protected static string $resource = MailArchiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
