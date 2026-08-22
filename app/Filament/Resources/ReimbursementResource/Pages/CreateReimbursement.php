<?php

namespace App\Filament\Resources\ReimbursementResource\Pages;

use App\Filament\Resources\ReimbursementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReimbursement extends CreateRecord
{
    protected static string $resource = ReimbursementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['total_amount'] = collect($data['items'] ?? [])->sum('amount');
        $data['submitted_at'] = now();

        return $data;
    }
}
