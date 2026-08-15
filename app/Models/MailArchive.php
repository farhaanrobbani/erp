<?php

namespace App\Models;

use App\Enums\MailArchiveType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MailArchive extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'type' => MailArchiveType::class,
            'letter_date' => 'date',
            'received_date' => 'date',
        ];
    }

    public function letterCategory(): BelongsTo
    {
        return $this->belongsTo(LetterCategory::class);
    }

    public function letterRequest(): BelongsTo
    {
        return $this->belongsTo(LetterRequest::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function dispositions(): HasMany
    {
        return $this->hasMany(Disposition::class);
    }
}
