<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use Illuminate\Database\Seeder;

class CompanyProfileSeeder extends Seeder
{
    public function run(): void
    {
        CompanyProfile::setValue('general.company_name', 'PT Karya Nusantara Konstruksi', 'general');
        CompanyProfile::setValue('general.slogan', 'Membangun Infrastruktur, Menghadirkan Kepercayaan.', 'general');
        CompanyProfile::setValue('general.established_year', '2008', 'general');

        CompanyProfile::setValue('hero.badge', 'Terdaftar & terverifikasi untuk pengadaan BUMN/LPSE', 'hero');
        CompanyProfile::setValue('hero.title', 'Membangun Infrastruktur, Menghadirkan ', 'hero');
        CompanyProfile::setValue('hero.title_highlight', 'Kepercayaan.', 'hero');
        CompanyProfile::setValue('hero.subtitle', 'Kontraktor nasional dengan track record proyek BUMN, sertifikasi K3, dan tim profesional yang siap menghadirkan hasil terbaik di setiap proyek.', 'hero');
        CompanyProfile::setValue('hero.cta_primary', 'Diskusikan Proyek', 'hero');
        CompanyProfile::setValue('hero.cta_secondary', 'Unduh Company Profile', 'hero');
        CompanyProfile::setJsonValue('hero.stats', [
            ['value' => '150+', 'label' => 'Proyek Selesai'],
            ['value' => '30+', 'label' => 'Klien BUMN'],
            ['value' => '5.000.000+', 'label' => 'Jam Kerja Selamat'],
            ['value' => 'ISO', 'label' => '9001 / 14001 / 45001'],
        ], 'hero');

        CompanyProfile::setValue('about.title', 'Mitra Andal untuk Proyek Strategis Nasional', 'about');
        CompanyProfile::setValue('about.body', 'Berdiri sejak 2008, kami melayani konstruksi gedung, infrastruktur sipil, dan instalasi MEP dengan standar keselamatan internasional. Didukung legalitas lengkap (NIB, SBU, SKK, ISO) sehingga siap mengikuti setiap tender BUMN maupun swasta.', 'about');
        CompanyProfile::setJsonValue('about.points', [
            'SBU Kualifikasi Besar — Bidang Bangunan Gedung & Sipil',
            'Terdaftar di LPSE & e-Catalogue Kementerian PUPR',
            'Tenaga ahli bersertifikat SKK/SKA',
            'Sistem Manajemen K3 teraudit',
        ], 'about');
        CompanyProfile::setJsonValue('about.stats', [
            ['value' => '2008', 'label' => 'Tahun Berdiri'],
            ['value' => '5.2T+', 'label' => 'Nilai Kontrak'],
            ['value' => '99.98%', 'label' => 'Tingkat Zero Accident'],
            ['value' => '500+', 'label' => 'Tenaga Profesional'],
        ], 'about');

        CompanyProfile::setJsonValue('services', [
            ['title' => 'Konstruksi', 'desc' => 'Pembangunan gedung bertingkat, pabrik, gudang, dan fasilitas komersial.'],
            ['title' => 'Teknik Sipil', 'desc' => 'Jalan, jembatan, drainase, dan infrastruktur penunjang lainnya.'],
            ['title' => 'MEP', 'desc' => 'Instalasi mekanikal, elektrikal, dan plumbing sesuai standar.'],
        ], 'services');

        CompanyProfile::setValue('hse.title', 'Keselamatan adalah Prioritas Mutlak', 'hse');
        CompanyProfile::setValue('hse.body', 'Kami menerapkan Sistem Manajemen K3 (SMK3) yang berkelanjutan: safety induction wajib bagi seluruh pekerja, inspeksi alat berkala, pelaporan insiden transparan, dan pencatatan Jam Kerja Selamat (Zero Accident) setiap periode proyek.', 'hse');
        CompanyProfile::setJsonValue('hse.stats', [
            ['value' => '0', 'label' => 'Kecelakaan Fatal'],
            ['value' => '5JT+', 'label' => 'Jam Kerja Selamat'],
        ], 'hse');
        CompanyProfile::setValue('hse.cert_label', 'Sertifikasi K3 Umum & Ahli K3 Konstruksi', 'hse');

        CompanyProfile::setValue('contact.address', 'Jl. Raya Contoh No. 88, Jakarta Selatan', 'contact');
        CompanyProfile::setValue('contact.phone', '+62 21 5555 8888', 'contact');
        CompanyProfile::setValue('contact.email', 'info@karya-nusantara.co.id', 'contact');
        CompanyProfile::setValue('contact.social_instagram', 'https://instagram.com/karyanusantara', 'contact');
        CompanyProfile::setValue('contact.social_linkedin', 'https://linkedin.com/company/karyanusantara', 'contact');
        CompanyProfile::setValue('contact.social_facebook', 'https://facebook.com/karyanusantara', 'contact');
    }
}
