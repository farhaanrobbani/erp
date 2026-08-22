<?php

namespace App\Filament\Resources\SafetyInductionResource\Pages;

use App\Filament\Resources\SafetyInductionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSafetyInduction extends EditRecord
{
    protected static string $resource = SafetyInductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
