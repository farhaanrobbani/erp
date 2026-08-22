<?php

namespace App\Filament\Resources\PayrollResource\Pages;

use App\Filament\Resources\PayrollResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPayroll extends EditRecord
{
    protected static string $resource = PayrollResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $income = ($data['base_salary'] ?? 0) + ($data['project_allowance'] ?? 0)
            + ($data['transport_allowance'] ?? 0) + ($data['overtime'] ?? 0);

        $details = collect($data['details'] ?? []);
        $addAllow = $details->where('type', 'allowance')->sum('amount');
        $addDeduct = $details->where('type', 'deduction')->sum('amount');

        $totalDed = ($data['deduction_total'] ?? 0) + ($data['tax'] ?? 0)
            + ($data['bpjs_amount'] ?? 0) + $addDeduct;

        $data['net_salary'] = $income + $addAllow - $totalDed;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
