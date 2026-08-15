<?php

namespace Database\Seeders;

use App\Models\LetterCategory;
use Illuminate\Database\Seeder;

class LetterCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'code' => 'MEMO',
                'name' => 'Memo Internal',
                'code_format' => '{NUMBER}/MEMO/{ROMAN}/{YEAR}',
                'pad_length' => 3,
                'description' => 'Memo internal perusahaan',
            ],
            [
                'code' => 'SPK',
                'name' => 'Surat Perintah Kerja',
                'code_format' => '{NUMBER}/SPK/{ROMAN}/{YEAR}',
                'pad_length' => 3,
                'description' => 'Surat perintah kerja / penugasan',
            ],
            [
                'code' => 'KET',
                'name' => 'Surat Keterangan',
                'code_format' => '{NUMBER}/KET/{ROMAN}/{YEAR}',
                'pad_length' => 3,
                'description' => 'Surat keterangan kerja atau lainnya',
            ],
            [
                'code' => 'SK',
                'name' => 'Surat Keputusan',
                'code_format' => '{NUMBER}/SK/{ROMAN}/{YEAR}',
                'pad_length' => 3,
                'description' => 'Surat keputusan direksi',
            ],
            [
                'code' => 'TND',
                'name' => 'Surat Tanda Terima',
                'code_format' => '{NUMBER}/TND/{ROMAN}/{YEAR}',
                'pad_length' => 3,
                'description' => 'Tanda terima dokumen',
            ],
            [
                'code' => 'UND',
                'name' => 'Undangan',
                'code_format' => '{NUMBER}/UND/{ROMAN}/{YEAR}',
                'pad_length' => 3,
                'description' => 'Surat undangan rapat / acara',
            ],
        ];

        foreach ($categories as $category) {
            LetterCategory::updateOrCreate(['code' => $category['code']], $category);
        }
    }
}
