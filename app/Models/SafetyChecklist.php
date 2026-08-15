<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafetyChecklist extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'check_date' => 'date',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
