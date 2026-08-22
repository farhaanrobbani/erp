<?php

namespace App\Filament\Resources\SafetyHourResource\Pages;

use App\Filament\Resources\SafetyHourResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSafetyHours extends ListRecords
{
    protected static string $resource = SafetyHourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
