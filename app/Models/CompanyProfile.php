<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $guarded = [];

    public static function value(string $key, ?string $default = null): ?string
    {
        $row = static::where('key', $key)->where('is_active', true)->first();

        return $row?->value ?? $default;
    }

    public static function jsonValue(string $key, array $default = []): array
    {
        $raw = static::value($key);

        if (! $raw) {
            return $default;
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : $default;
    }

    public static function setValue(string $key, ?string $value, ?string $section = null): void
    {
        static::updateOrCreate(['key' => $key], [
            'section' => $section ?? explode('.', $key)[0] ?? 'general',
            'value' => (string) $value,
        ]);
    }

    public static function setJsonValue(string $key, array $value, ?string $section = null): void
    {
        static::setValue($key, json_encode($value), $section);
    }
}
