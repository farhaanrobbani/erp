<?php

namespace App\Models;

use App\Enums\PayrollStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'base_salary' => 'decimal:2',
            'project_allowance' => 'decimal:2',
            'transport_allowance' => 'decimal:2',
            'overtime' => 'decimal:2',
            'deduction_total' => 'decimal:2',
            'tax' => 'decimal:2',
            'bpjs_amount' => 'decimal:2',
            'net_salary' => 'decimal:2',
            'status' => PayrollStatus::class,
            'paid_at' => 'datetime',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(PayrollDetail::class);
    }
}
