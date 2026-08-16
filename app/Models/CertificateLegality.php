<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateLegality extends Model
{
    protected $table = 'certificates_legalities';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiry_date' => 'date',
            'is_active' => 'boolean',
            'type' => \App\Enums\CertificateType::class,
        ];
    }
}
