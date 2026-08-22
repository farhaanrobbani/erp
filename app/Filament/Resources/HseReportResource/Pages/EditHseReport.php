<?php

namespace App\Filament\Resources\HseReportResource\Pages;

use App\Filament\Resources\HseReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHseReport extends EditRecord
{
    protected static string $resource = HseReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
