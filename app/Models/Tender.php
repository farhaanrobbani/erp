<?php

namespace App\Models;

use App\Enums\TenderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tender extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
            'status' => TenderStatus::class,
            'bid_date' => 'date',
            'result_date' => 'date',
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(TenderDocument::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getBudgetFormattedAttribute(): string
    {
        return $this->budget ? 'Rp ' . number_format((float) $this->budget, 0, ',', '.') : '-';
    }
}
