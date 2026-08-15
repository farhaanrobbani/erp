<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileManager extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'access_roles' => 'array',
        ];
    }

    public function folder()
    {
        return $this->belongsTo(self::class, 'folder_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'folder_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
