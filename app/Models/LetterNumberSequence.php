<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterNumberSequence extends Model
{
    protected $guarded = [];

    public function letterCategory()
    {
        return $this->belongsTo(LetterCategory::class);
    }
}
