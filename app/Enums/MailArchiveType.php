<?php

namespace App\Enums;

enum MailArchiveType: string
{
    case Incoming = 'incoming';
    case Outgoing = 'outgoing';

    public function label(): string
    {
        return match ($this) {
            self::Incoming => 'Surat Masuk',
            self::Outgoing => 'Surat Keluar',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Incoming => 'primary',
            self::Outgoing => 'info',
        };
    }
}
