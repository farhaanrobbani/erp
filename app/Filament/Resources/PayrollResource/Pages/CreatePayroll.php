<?php

namespace App\Filament\Resources\PayrollResource\Pages;

use App\Filament\Resources\PayrollResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayroll extends CreateRecord
{
    protected static string $resource = PayrollResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $income = ($data['base_salary'] ?? 0) + ($data['project_allowance'] ?? 0) + ($data['transport_allowance'] ?? 0) + ($data['overtime'] ?? 0);

        $details = collect($data['details'] ?? []);
        $additionalAllowance = $details->where('type', 'allowance')->sum('amount');
        $additionalDeduction = $details->where('type', 'deduction')->sum('amount');

        $totalDeductions = ($data['deduction_total'] ?? 0) + ($data['tax'] ?? 0) + ($data['bpjs_amount'] ?? 0) + $additionalDeduction;

        $data['net_salary'] = $income + $additionalAllowance - $totalDeductions;

        return $data;
    }
}
