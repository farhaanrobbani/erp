<?php

namespace App\Enums;

enum LeaveType: string
{
    case Annual = 'annual';
    case Sick = 'sick';
    case Permission = 'permission';
    case Maternity = 'maternity';
    case Unpaid = 'unpaid';

    public function label(): string
    {
        return match ($this) {
            self::Annual => 'Cuti Tahunan',
            self::Sick => 'Sakit',
            self::Permission => 'Izin',
            self::Maternity => 'Cuti Melahirkan',
            self::Unpaid => 'Cuti Tidak Dibayar',
        };
    }
}
