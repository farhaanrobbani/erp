<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LetterCategory extends Model
{
    protected $guarded = [];

    public function requests(): HasMany
    {
        return $this->hasMany(LetterRequest::class);
    }

    public function numberSequences(): HasMany
    {
        return $this->hasMany(LetterNumberSequence::class);
    }
}
