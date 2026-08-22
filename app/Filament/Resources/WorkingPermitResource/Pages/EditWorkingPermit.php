<?php

namespace App\Filament\Resources\WorkingPermitResource\Pages;

use App\Filament\Resources\WorkingPermitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkingPermit extends EditRecord
{
    protected static string $resource = WorkingPermitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
