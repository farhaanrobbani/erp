<?php

namespace App\Enums;

enum JobVacancyType: string
{
    case Fulltime = 'fulltime';
    case Contract = 'contract';

    public function label(): string
    {
        return match ($this) {
            self::Fulltime => 'Kontrak Tetap (Full-time)',
            self::Contract => 'Kontrak Proyek',
        };
    }
}
