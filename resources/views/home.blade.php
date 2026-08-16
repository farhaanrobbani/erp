@extends('layouts.public')

@section('content')

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-900 via-blue-950 to-blue-950 pt-32 pb-20 text-white">
        <div class="absolute inset-0 opacity-30"
             style="background-image: radial-gradient(circle at 20% 30%, #3b82f633, transparent 45%), radial-gradient(circle at 80% 70%, #0ea5e933, transparent 40%);">
        </div>
        <div class="relative mx-auto max-w-7xl px-6">
            <p class="mb-4 inline-flex items-center gap-2 rounded-full border border-blue-400/40 bg-blue-500/10 px-4 py-1 text-xs font-semibold text-blue-300">
                Terdaftar &amp; terverifikasi untuk pengadaan BUMN/LPSE
            </p>
            <h1 class="max-w-3xl text-4xl font-extrabold leading-tight md:text-6xl">
                Membangun Infrastruktur,<br>Menghadirkan <span class="text-blue-300">Kepercayaan.</span>
            </h1>
            <p class="mt-6 max-w-2xl text-lg text-slate-300">
                Kontraktor nasional dengan track record proyek BUMN, sertifikasi K3, dan tim profesional yang siap
                menghadirkan hasil terbaik di setiap proyek.
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="#kontak" class="rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500">Diskusikan Proyek</a>
                <a href="#" class="rounded-lg border border-white/20 px-6 py-3 text-sm font-semibold hover:bg-white/10">Unduh Company Profile</a>
            </div>
            <div class="mt-14 grid grid-cols-2 gap-6 border-t border-white/10 pt-8 md:grid-cols-4">
                <div><p class="text-3xl font-extrabold text-blue-300">150+</p><p class="mt-1 text-sm text-slate-400">Proyek Selesai</p></div>
                <div><p class="text-3xl font-extrabold text-blue-300">30+</p><p class="mt-1 text-sm text-slate-400">Klien BUMN</p></div>
                <div><p class="text-3xl font-extrabold text-blue-300">5.000.000+</p><p class="mt-1 text-sm text-slate-400">Jam Kerja Selamat</p></div>
                <div><p class="text-3xl font-extrabold text-blue-300">ISO</p><p class="mt-1 text-sm text-slate-400">9001 / 14001 / 45001</p></div>
            </div>
        </div>
    </section>

    {{-- Tentang --}}
    <section id="tentang" class="py-20">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-12 px-6 md:grid-cols-2">
            <div>
                <p class="text-sm font-semibold text-blue-600">TENTANG KAMI</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Mitra Andal untuk Proyek Strategis Nasional</h2>
                <p class="mt-4 leading-relaxed text-slate-600">
                    Berdiri sejak 2008, kami melayani konstruksi gedung, infrastruktur sipil, dan instalasi MEP dengan
                    standar keselamatan internasional. Didukung legalitas lengkap (NIB, SBU, SKK, ISO) sehingga siap
                    mengikuti setiap tender BUMN maupun swasta.
                </p>
                <ul class="mt-6 space-y-3 text-slate-700">
                    <li class="flex gap-3"><span class="text-blue-600">✔</span> SBU Kualifikasi Besar — Bidang Bangunan Gedung &amp; Sipil</li>
                    <li class="flex gap-3"><span class="text-blue-600">✔</span> Terdaftar di LPSE &amp; e-Catalogue Kementerian PUPR</li>
                    <li class="flex gap-3"><span class="text-blue-600">✔</span> Tenaga ahli bersertifikat SKK/SKA</li>
                    <li class="flex gap-3"><span class="text-blue-600">✔</span> Sistem Manajemen K3 teraudit</li>
                </ul>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-2xl bg-slate-100 p-6"><p class="text-4xl font-extrabold text-slate-900">2008</p><p class="mt-2 text-sm text-slate-600">Tahun Berdiri</p></div>
                <div class="rounded-2xl bg-slate-100 p-6"><p class="text-4xl font-extrabold text-slate-900">5.2T+</p><p class="mt-2 text-sm text-slate-600">Nilai Kontrak</p></div>
                <div class="rounded-2xl bg-slate-100 p-6"><p class="text-4xl font-extrabold text-slate-900">99.98%</p><p class="mt-2 text-sm text-slate-600">Tingkat Zero Accident</p></div>
                <div class="rounded-2xl bg-slate-100 p-6"><p class="text-4xl font-extrabold text-slate-900">500+</p><p class="mt-2 text-sm text-slate-600">Tenaga Profesional</p></div>
            </div>
        </div>
    </section>

    {{-- Layanan --}}
    <section id="layanan" class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-6">
            <p class="text-sm font-semibold text-blue-600">BIDANG USAHA</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900">Layanan Kami</h2>
            <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach ([
                    ['Konstruksi', 'Pembangunan gedung bertingkat, pabrik, gudang, dan fasilitas komersial.'],
                    ['Teknik Sipil', 'Jalan, jembatan, drainase, dan infrastruktur penunjang lainnya.'],
                    ['MEP', 'Instalasi mekanikal, elektrikal, dan plumbing sesuai standar.'],
                ] as [$title, $desc])
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md">
                        <div class="h-10 w-10 rounded-lg bg-blue-600/15 text-center text-xl">🏗️</div>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Portofolio --}}
    <section id="portofolio" class="py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-sm font-semibold text-blue-600">PORTOFOLIO</p>
                    <h2 class="mt-2 text-3xl font-bold text-slate-900">Proyek Unggulan</h2>
                </div>
                <a href="#" class="text-sm font-semibold text-blue-600 hover:underline">Lihat semua →</a>
            </div>
            <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach ([
                    ['Pembangunan Gedung Kantor PLN', 'Jakarta', 'Berjalan', 'Rp 250 M'],
                    ['Jalan Tol Akses Pelabuhan', 'Surabaya', 'Selesai', 'Rp 1,2 T'],
                    ['Instalasi MEP Rumah Sakit BUMN', 'Bandung', 'Selesai', 'Rp 85 M'],
                ] as [$name, $loc, $status, $value])
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex h-40 items-center justify-center bg-slate-200 text-4xl">🏢</div>
                        <div class="p-5">
                            <h3 class="font-semibold text-slate-900">{{ $name }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $loc }}</p>
                            <div class="mt-3 flex items-center justify-between text-sm">
                                <span class="rounded-full bg-{{ $status === 'Selesai' ? 'green' : 'blue' }}-100 px-3 py-1 text-xs font-semibold text-{{ $status === 'Selesai' ? 'green' : 'blue' }}-700">{{ $status }}</span>
                                <span class="font-semibold text-slate-800">{{ $value }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- HSE / K3 --}}
    <section id="k3" class="bg-blue-950 py-20 text-white">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-1 gap-12 md:grid-cols-2">
                <div>
                    <p class="text-sm font-semibold text-blue-300">HSE / K3</p>
                    <h2 class="mt-2 text-3xl font-bold">Keselamatan adalah Prioritas Mutlak</h2>
                    <p class="mt-4 leading-relaxed text-slate-300">
                        Kami menerapkan Sistem Manajemen K3 (SMK3) yang berkelanjutan: safety induction wajib bagi
                        seluruh pekerja, inspeksi alat berkala, pelaporan insiden transparan, dan pencatatan Jam Kerja
                        Selamat (Zero Accident) setiap periode proyek.
                    </p>
                    <div class="mt-6 grid grid-cols-2 gap-4">
                        <div class="rounded-xl border border-white/10 p-4">
                            <p class="text-2xl font-extrabold text-blue-300">0</p>
                            <p class="text-sm text-slate-400">Kecelakaan Fatal</p>
                        </div>
                        <div class="rounded-xl border border-white/10 p-4">
                            <p class="text-2xl font-extrabold text-blue-300">5JT+</p>
                            <p class="text-sm text-slate-400">Jam Kerja Selamat</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-center rounded-2xl bg-white/5 p-10">
                    <p class="text-center text-4xl">🦺 <span class="block mt-4 text-sm text-slate-400">Sertifikasi K3 Umum &amp; Ahli K3 Konstruksi</span></p>
                </div>
            </div>
        </div>
    </section>

    {{-- Kontak --}}
    <section id="kontak" class="py-20">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-12 px-6 md:grid-cols-2">
            <div>
                <p class="text-sm font-semibold text-blue-600">HUBUNGI KAMI</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Siap Bekerja Sama?</h2>
                <p class="mt-4 text-slate-600">Tim kami siap membahas kebutuhan proyek Anda. Isi formulir atau hubungi kami langsung.</p>
                <div class="mt-6 space-y-3 text-sm text-slate-700">
                    <p>📍 Jl. Raya Contoh No. 88, Jakarta Selatan</p>
                    <p>📞 +62 21 5555 8888</p>
                    <p>✉️ info@karya-nusantara.co.id</p>
                </div>
            </div>
            <form action="/kontak" method="POST" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                @csrf
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <input name="name" type="text" placeholder="Nama" required class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
                    <input name="email" type="email" placeholder="Email" required class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
                </div>
                <input name="phone" type="tel" placeholder="No. HP" class="mt-4 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
                <input name="subject" type="text" placeholder="Perihal" required class="mt-4 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
                <textarea name="message" placeholder="Pesan" required rows="4" class="mt-4 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none"></textarea>
                <button type="submit" class="mt-5 w-full rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">Kirim Pesan</button>
            </form>
        </div>
    </section>
@endsection
