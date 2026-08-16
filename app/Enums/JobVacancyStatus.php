<?php

namespace App\Enums;

enum JobVacancyStatus: string
{
    case Open = 'open';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Dibuka',
            self::Closed => 'Ditutup',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Open => 'success',
            self::Closed => 'gray',
        };
    }
}
