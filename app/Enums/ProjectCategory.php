<?php

namespace App\Enums;

enum ProjectCategory: string
{
    case Construction = 'construction';
    case Civil = 'civil';
    case Mep = 'mep';
    case Interior = 'interior';
    case Maintenance = 'maintenance';

    public function label(): string
    {
        return match ($this) {
            self::Construction => 'Konstruksi',
            self::Civil => 'Sipil',
            self::Mep => 'MEP (Mekanikal-Elektrikal-Plumbing)',
            self::Interior => 'Interior',
            self::Maintenance => 'Pemeliharaan',
        };
    }
}
