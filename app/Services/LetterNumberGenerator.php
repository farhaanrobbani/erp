<?php

namespace App\Services;

use App\Models\LetterCategory;
use App\Models\LetterNumberSequence;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LetterNumberGenerator
{
    private const ROMAN = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII',
    ];

    /**
     * Generate nomor surat keluar secara atomik & anti-duplikat.
     *
     * Wajib dipanggil di dalam transaksi database. Mengunci baris counter
     * (SELECT ... FOR UPDATE) agar dua approval bersamaan tidak pernah
     * menghasilkan nomor urut yang sama.
     *
     * Format default: 004/MEMO/III/2026  ->  {NUMBER}/{CODE}/{ROMAN}/{YEAR}
     */
    public function generate(LetterCategory $category, Carbon|string $date): string
    {
        $date = Carbon::parse($date);
        $month = $date->month;
        $year = $date->year;

        // 1. Lock baris counter kategori + bulan + tahun
        $sequence = LetterNumberSequence::query()
            ->where('letter_category_id', $category->id)
            ->where('period_month', $month)
            ->where('period_year', $year)
            ->lockForUpdate()
            ->first();

        // 2. Buat counter baru jika belum ada, atau increment atomik
        if (! $sequence) {
            $sequence = LetterNumberSequence::create([
                'letter_category_id' => $category->id,
                'period_month' => $month,
                'period_year' => $year,
                'last_number' => 1,
            ]);
            $number = 1;
        } else {
            $number = $sequence->last_number + 1;
            $sequence->update(['last_number' => $number]);
        }

        // 3. Isi placeholder sesuai code_format kategori
        return str_replace(
            ['{NUMBER}', '{CODE}', '{ROMAN}', '{YEAR}'],
            [
                str_pad((string) $number, $category->pad_length, '0', STR_PAD_LEFT),
                $category->code,
                self::ROMAN[$month],
                (string) $year,
            ],
            $category->code_format
        );
    }

    /**
     * Pratinjau nomor berikutnya tanpa mengubah counter (untuk preview form).
     */
    public function preview(LetterCategory $category, Carbon|string $date): string
    {
        $date = Carbon::parse($date);
        $next = DB::table('letter_number_sequences')
            ->where('letter_category_id', $category->id)
            ->where('period_month', $date->month)
            ->where('period_year', $date->year)
            ->value('last_number');

        $number = ((int) $next) + 1;

        return str_replace(
            ['{NUMBER}', '{CODE}', '{ROMAN}', '{YEAR}'],
            [
                str_pad((string) $number, $category->pad_length, '0', STR_PAD_LEFT),
                $category->code,
                self::ROMAN[$date->month],
                (string) $date->year,
            ],
            $category->code_format
        );
    }
}
