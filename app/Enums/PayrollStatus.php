<?php

namespace App\Enums;

enum PayrollStatus: string
{
    case Draft = 'draft';
    case Computed = 'computed';
    case Approved = 'approved';
    case Paid = 'paid';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Computed => 'Terhitung',
            self::Approved => 'Disetujui',
            self::Paid => 'Dibayar',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Computed => 'info',
            self::Approved => 'primary',
            self::Paid => 'success',
        };
    }
}
