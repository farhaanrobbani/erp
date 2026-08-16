<?php

namespace App\Enums;

enum CareerApplicationStatus: string
{
    case New = 'new';
    case Reviewed = 'reviewed';
    case Interview = 'interview';
    case Hired = 'hired';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Baru',
            self::Reviewed => 'Direview',
            self::Interview => 'Wawancara',
            self::Hired => 'Diterima',
            self::Rejected => 'Ditolak',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::New => 'info',
            self::Reviewed => 'warning',
            self::Interview => 'primary',
            self::Hired => 'success',
            self::Rejected => 'danger',
        };
    }
}
