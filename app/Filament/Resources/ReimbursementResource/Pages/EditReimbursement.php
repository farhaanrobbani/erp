<?php

namespace App\Filament\Resources\ReimbursementResource\Pages;

use App\Filament\Resources\ReimbursementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReimbursement extends EditRecord
{
    protected static string $resource = ReimbursementResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['total_amount'] = collect($data['items'] ?? [])->sum('amount');

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
