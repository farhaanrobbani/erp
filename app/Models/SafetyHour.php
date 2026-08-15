<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafetyHour extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'period' => 'date',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
