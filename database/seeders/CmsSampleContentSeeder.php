<?php

namespace Database\Seeders;

use App\Enums\PostCategory;
use App\Enums\ProjectClientType;
use App\Enums\ProjectCategory;
use App\Enums\ProjectStatus;
use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectGallery;
use App\Models\User;
use Illuminate\Database\Seeder;

class CmsSampleContentSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::role('super_admin')->first() ?? User::first();

        $projects = [
            [
                'name' => 'Pembangunan Gedung Kantor PLN',
                'slug' => 'pembangunan-gedung-kantor-pln',
                'client_name' => 'PT PLN (Persero)',
                'client_type' => ProjectClientType::Bumn,
                'category' => ProjectCategory::Construction,
                'value' => 250000000000,
                'status' => ProjectStatus::Ongoing,
                'location' => 'Jakarta',
                'start_date' => '2026-02-01',
                'end_date' => '2028-01-31',
                'description' => 'Pembangunan gedung kantor pusat PLN dengan konsep green building, 20 lantai, dilengkapi instalasi MEP terintegrasi dan sertifikasi greenship.',
                'is_featured' => true,
                'is_published' => true,
                'galleries' => [],
            ],
            [
                'name' => 'Jalan Tol Akses Pelabuhan',
                'slug' => 'jalan-tol-akses-pelabuhan',
                'client_name' => 'Kementerian PUPR',
                'client_type' => ProjectClientType::Government,
                'category' => ProjectCategory::Civil,
                'value' => 1200000000000,
                'status' => ProjectStatus::Completed,
                'location' => 'Surabaya',
                'start_date' => '2023-03-01',
                'end_date' => '2025-12-31',
                'description' => 'Pembangunan jalan tol sepanjang 24 km menghubungkan pelabuhan utama dengan jaringan tol nasional, termasuk 3 jembatan dan 2 interchange.',
                'is_featured' => true,
                'is_published' => true,
                'galleries' => [],
            ],
            [
                'name' => 'Instalasi MEP Rumah Sakit BUMN',
                'slug' => 'instalasi-mep-rumah-sakit-bumn',
                'client_name' => 'Rumah Sakit BUMN',
                'client_type' => ProjectClientType::Bumn,
                'category' => ProjectCategory::Mep,
                'value' => 85000000000,
                'status' => ProjectStatus::Completed,
                'location' => 'Bandung',
                'start_date' => '2024-06-01',
                'end_date' => '2026-05-31',
                'description' => 'Pekerjaan mekanikal, elektrikal, dan plumbing untuk rumah sakit 300 tempat tidur, termasuk sistem tata udara presisi, genset, dan gas medis.',
                'is_featured' => true,
                'is_published' => true,
                'galleries' => [],
            ],
        ];

        foreach ($projects as $data) {
            $galleries = $data['galleries'];
            unset($data['galleries']);

            $project = Project::updateOrCreate(['slug' => $data['slug']], $data);

            foreach ($galleries as $i => $gallery) {
                ProjectGallery::updateOrCreate(
                    ['project_id' => $project->id, 'sort_order' => $i],
                    $gallery
                );
            }
        }

        $posts = [
            [
                'title' => 'Kami Tandatangani Kontrak Proyek Rp 250 Miliar dengan PLN',
                'slug' => 'kontrak-proyek-250-miliar-pln',
                'excerpt' => 'Penandatanganan kontrak pembangunan gedung kantor PLN menandai kepercayaan BUMN terhadap kualitas pekerjaan kami.',
                'body' => '<p>PT Karya Nusantara Konstruksi resmi menandatangani kontrak pembangunan gedung kantor PLN senilai Rp 250 miliar. Proyek ini akan dikerjakan dalam kurun waktu dua tahun dengan standar green building dan sertifikasi K3 yang ketat.</p><p>Direktur Utama menyampaikan bahwa kepercayaan ini menjadi bukti konsistensi perusahaan dalam menjaga mutu dan keselamatan kerja.</p>',
                'category' => PostCategory::News,
                'is_published' => true,
            ],
            [
                'title' => 'Lulus Audit ISO 45001: Sistem Manajemen K3 Kami Terbaik',
                'slug' => 'lulus-audit-iso-45001',
                'excerpt' => 'Audit eksternal menilai penerapan SMK3 kami berjalan efektif dan layak dipertahankan.',
                'body' => '<p>Perusahaan berhasil mempertahankan sertifikasi ISO 45001 setelah melalui audit pengawasan tahunan. Audit mencakup seluruh proyek aktif, dokumentasi K3, dan kesiapan tanggap darurat.</p><p>Capaian ini memperkuat posisi kami di industri konstruksi nasional dan membuka peluang tender BUMN yang mensyaratkan sistem manajemen keselamatan yang matang.</p>',
                'category' => PostCategory::Article,
                'is_published' => true,
            ],
            [
                'title' => 'Open Recruitment: Siap Bergabung dengan Kami?',
                'slug' => 'open-recruitment',
                'excerpt' => 'Kami membuka lowongan untuk berbagai posisi teknis dan non-teknis untuk kebutuhan proyek 2026.',
                'body' => '<p>Seiring pertumbuhan proyek, kami membuka rekrutmen untuk posisi site engineer, HSE officer, quantity surveyor, dan admin keuangan. Informasi lengkap tersedia di halaman Karier.</p><p>Kirim lamaran Anda melalui halaman Karier dengan melampirkan CV terbaru.</p>',
                'category' => PostCategory::Announcement,
                'is_published' => true,
            ],
        ];

        foreach ($posts as $data) {
            $data['author_id'] = $author?->id;
            $data['published_at'] = now()->subDays(rand(1, 30));

            Post::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
