<?php

namespace App\Filament\Resources\IncidentLogResource\Pages;

use App\Filament\Resources\IncidentLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncidentLogs extends ListRecords
{
    protected static string $resource = IncidentLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
