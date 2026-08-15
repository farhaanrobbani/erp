<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HseReport extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
