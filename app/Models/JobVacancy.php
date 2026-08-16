<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobVacancy extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'type' => \App\Enums\JobVacancyType::class,
            'status' => \App\Enums\JobVacancyStatus::class,
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(CareerApplication::class);
    }
}
