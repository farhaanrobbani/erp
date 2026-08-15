<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mcu extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'mcu_date' => 'date',
            'next_mcu_date' => 'date',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
