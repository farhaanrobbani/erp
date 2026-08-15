<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerApplication extends Model
{
    protected $guarded = [];

    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class);
    }
}
