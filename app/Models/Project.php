<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'status' => \App\Enums\ProjectStatus::class,
            'client_type' => \App\Enums\ProjectClientType::class,
            'category' => \App\Enums\ProjectCategory::class,
            'start_date' => 'date',
            'end_date' => 'date',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(ProjectGallery::class)->orderBy('sort_order');
    }

    public function getValueFormattedAttribute(): string
    {
        return $this->value ? 'Rp ' . number_format((float) $this->value, 0, ',', '.') : '-';
    }
}
