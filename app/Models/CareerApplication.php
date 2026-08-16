<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerApplication extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => \App\Enums\CareerApplicationStatus::class,
        ];
    }

    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class);
    }
}
