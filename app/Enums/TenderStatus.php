<?php

namespace App\Enums;

enum TenderStatus: string
{
    case Announcement = 'announcement';
    case Kualifikasi = 'kualifikasi';
    case Anwijzing = 'anwijzing';
    case Penawaran = 'penawaran';
    case Menang = 'menang';
    case Kalah = 'kalah';
    case Canceled = 'canceled';

    public function label(): string
    {
        return match ($this) {
            self::Announcement => 'Pengumuman',
            self::Kualifikasi => 'Pembuktian Kualifikasi',
            self::Anwijzing => 'Anwijzing',
            self::Penawaran => 'Penawaran',
            self::Menang => 'Menang',
            self::Kalah => 'Kalah',
            self::Canceled => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Announcement => 'gray',
            self::Kualifikasi => 'primary',
            self::Anwijzing => 'info',
            self::Penawaran => 'warning',
            self::Menang => 'success',
            self::Kalah => 'danger',
            self::Canceled => 'gray',
        };
    }
}
