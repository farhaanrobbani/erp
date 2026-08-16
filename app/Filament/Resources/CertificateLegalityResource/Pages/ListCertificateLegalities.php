<?php

namespace App\Filament\Resources\CertificateLegalityResource\Pages;

use App\Filament\Resources\CertificateLegalityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCertificateLegalities extends ListRecords
{
    protected static string $resource = CertificateLegalityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
