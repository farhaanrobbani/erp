<?php

namespace App\Enums;

enum PostCategory: string
{
    case News = 'news';
    case Article = 'article';
    case Announcement = 'announcement';

    public function label(): string
    {
        return match ($this) {
            self::News => 'Berita',
            self::Article => 'Artikel',
            self::Announcement => 'Pengumuman',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::News => 'info',
            self::Article => 'success',
            self::Announcement => 'warning',
        };
    }
}
