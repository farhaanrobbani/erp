<?php

namespace App\Filament\Resources\MailArchiveResource\Pages;

use App\Filament\Resources\MailArchiveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMailArchives extends ListRecords
{
    protected static string $resource = MailArchiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
