<?php

namespace App\Models;

use App\Enums\LetterRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LetterRequest extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => LetterRequestStatus::class,
            'request_date' => 'date',
            'approved_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function letterCategory(): BelongsTo
    {
        return $this->belongsTo(LetterCategory::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function mailArchive(): BelongsTo
    {
        return $this->belongsTo(MailArchive::class);
    }
}
