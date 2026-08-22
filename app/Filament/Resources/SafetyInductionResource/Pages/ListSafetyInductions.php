<?php

namespace App\Filament\Resources\SafetyInductionResource\Pages;

use App\Filament\Resources\SafetyInductionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSafetyInductions extends ListRecords
{
    protected static string $resource = SafetyInductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
