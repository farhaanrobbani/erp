<?php

namespace App\Filament\Resources\SafetyChecklistResource\Pages;

use App\Filament\Resources\SafetyChecklistResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSafetyChecklists extends ListRecords
{
    protected static string $resource = SafetyChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
