<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'category' => \App\Enums\PostCategory::class,
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
