<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderDocument extends Model
{
    protected $guarded = [];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }
}
