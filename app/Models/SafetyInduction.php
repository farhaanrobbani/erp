<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafetyInduction extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'induction_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
