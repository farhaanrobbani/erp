<?php

namespace App\Enums;

enum CertificateType: string
{
    case Nib = 'nib';
    case Sbu = 'sbu';
    case Skk = 'skk';
    case Ska = 'ska';
    case Iso9001 = 'iso_9001';
    case Iso14001 = 'iso_14001';
    case Iso45001 = 'iso_45001';
    case K3 = 'k3';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Nib => 'NIB',
            self::Sbu => 'SBU',
            self::Skk => 'SKK Konstruksi',
            self::Ska => 'SKA Ahli',
            self::Iso9001 => 'ISO 9001',
            self::Iso14001 => 'ISO 14001',
            self::Iso45001 => 'ISO 45001',
            self::K3 => 'Sertifikat K3',
            self::Other => 'Lainnya',
        };
    }
}
