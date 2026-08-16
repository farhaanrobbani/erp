<?php

namespace App\Enums;

enum ProjectClientType: string
{
    case Bumn = 'bumn';
    case Government = 'government';
    case Private = 'private';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Bumn => 'BUMN',
            self::Government => 'Pemerintah',
            self::Private => 'Swasta',
            self::Other => 'Lainnya',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Bumn => 'danger',
            self::Government => 'warning',
            self::Private => 'info',
            self::Other => 'gray',
        };
    }
}
