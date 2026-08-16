@extends('layouts.public')

@section('content')

    @php
        $companyName = \App\Models\CompanyProfile::value('general.company_name', 'PT Karya Nusantara Konstruksi');
        $heroStats = \App\Models\CompanyProfile::jsonValue('hero.stats');
        $aboutStats = \App\Models\CompanyProfile::jsonValue('about.stats');
        $aboutPoints = \App\Models\CompanyProfile::jsonValue('about.points');
        $services = \App\Models\CompanyProfile::jsonValue('services');
        $hseStats = \App\Models\CompanyProfile::jsonValue('hse.stats');
    @endphp

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-900 via-blue-950 to-blue-950 pt-32 pb-20 text-white">
        <div class="absolute inset-0 opacity-30"
             style="background-image: radial-gradient(circle at 20% 30%, #3b82f633, transparent 45%), radial-gradient(circle at 80% 70%, #0ea5e933, transparent 40%);">
        </div>
        <div class="relative mx-auto max-w-7xl px-6">
            <p class="mb-4 inline-flex items-center gap-2 rounded-full border border-blue-400/40 bg-blue-500/10 px-4 py-1 text-xs font-semibold text-blue-300">
                {{ \App\Models\CompanyProfile::value('hero.badge', 'Terdaftar & terverifikasi untuk pengadaan BUMN/LPSE') }}
            </p>
            <h1 class="max-w-3xl text-4xl font-extrabold leading-tight md:text-6xl">
                {{ \App\Models\CompanyProfile::value('hero.title', 'Membangun Infrastruktur, Menghadirkan ') }}<span class="text-blue-300">{{ \App\Models\CompanyProfile::value('hero.title_highlight', 'Kepercayaan.') }}</span>
            </h1>
            <p class="mt-6 max-w-2xl text-lg text-slate-300">
                {{ \App\Models\CompanyProfile::value('hero.subtitle') }}
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="#kontak" class="rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-500">{{ \App\Models\CompanyProfile::value('hero.cta_primary', 'Diskusikan Proyek') }}</a>
                <a href="{{ route('projects.index') }}" class="rounded-lg border border-white/20 px-6 py-3 text-sm font-semibold hover:bg-white/10">{{ \App\Models\CompanyProfile::value('hero.cta_secondary', 'Lihat Portofolio') }}</a>
            </div>
            <div class="mt-14 grid grid-cols-2 gap-6 border-t border-white/10 pt-8 md:grid-cols-4">
                @forelse ($heroStats as $stat)
                    <div><p class="text-3xl font-extrabold text-blue-300">{{ $stat['value'] ?? '-' }}</p><p class="mt-1 text-sm text-slate-400">{{ $stat['label'] ?? '' }}</p></div>
                @empty
                    <div><p class="text-3xl font-extrabold text-blue-300">150+</p><p class="mt-1 text-sm text-slate-400">Proyek Selesai</p></div>
                    <div><p class="text-3xl font-extrabold text-blue-300">30+</p><p class="mt-1 text-sm text-slate-400">Klien BUMN</p></div>
                    <div><p class="text-3xl font-extrabold text-blue-300">5.000.000+</p><p class="mt-1 text-sm text-slate-400">Jam Kerja Selamat</p></div>
                    <div><p class="text-3xl font-extrabold text-blue-300">ISO</p><p class="mt-1 text-sm text-slate-400">9001 / 14001 / 45001</p></div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Tentang --}}
    <section id="tentang" class="py-20">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-12 px-6 md:grid-cols-2">
            <div>
                <p class="text-sm font-semibold text-blue-600">TENTANG KAMI</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">{{ \App\Models\CompanyProfile::value('about.title', 'Mitra Andal untuk Proyek Strategis Nasional') }}</h2>
                <p class="mt-4 leading-relaxed text-slate-600">
                    {{ \App\Models\CompanyProfile::value('about.body') }}
                </p>
                <ul class="mt-6 space-y-3 text-slate-700">
                    @foreach ($aboutPoints as $point)
                        <li class="flex gap-3"><span class="text-blue-600">✔</span> {{ $point }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @forelse ($aboutStats as $stat)
                    <div class="rounded-2xl bg-slate-100 p-6"><p class="text-4xl font-extrabold text-slate-900">{{ $stat['value'] ?? '-' }}</p><p class="mt-2 text-sm text-slate-600">{{ $stat['label'] ?? '' }}</p></div>
                @empty
                    <div class="rounded-2xl bg-slate-100 p-6"><p class="text-4xl font-extrabold text-slate-900">2008</p><p class="mt-2 text-sm text-slate-600">Tahun Berdiri</p></div>
                    <div class="rounded-2xl bg-slate-100 p-6"><p class="text-4xl font-extrabold text-slate-900">5.2T+</p><p class="mt-2 text-sm text-slate-600">Nilai Kontrak</p></div>
                    <div class="rounded-2xl bg-slate-100 p-6"><p class="text-4xl font-extrabold text-slate-900">99.98%</p><p class="mt-2 text-sm text-slate-600">Tingkat Zero Accident</p></div>
                    <div class="rounded-2xl bg-slate-100 p-6"><p class="text-4xl font-extrabold text-slate-900">500+</p><p class="mt-2 text-sm text-slate-600">Tenaga Profesional</p></div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Layanan --}}
    <section id="layanan" class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-6">
            <p class="text-sm font-semibold text-blue-600">BIDANG USAHA</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900">Layanan Kami</h2>
            <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-3">
                @forelse ($services as $service)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md">
                        <div class="h-10 w-10 rounded-lg bg-blue-600/15 text-center text-xl">🏗️</div>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ $service['title'] ?? '' }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $service['desc'] ?? '' }}</p>
                    </div>
                @empty
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md">
                        <div class="h-10 w-10 rounded-lg bg-blue-600/15 text-center text-xl">🏗️</div>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">Konstruksi</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">Pembangunan gedung bertingkat, pabrik, gudang, dan fasilitas komersial.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md">
                        <div class="h-10 w-10 rounded-lg bg-blue-600/15 text-center text-xl">🛣️</div>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">Teknik Sipil</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">Jalan, jembatan, drainase, dan infrastruktur penunjang lainnya.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md">
                        <div class="h-10 w-10 rounded-lg bg-blue-600/15 text-center text-xl">⚡</div>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900">MEP</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">Instalasi mekanikal, elektrikal, dan plumbing sesuai standar.</p>
                    </div>
                @endforelse
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
                <a href="{{ route('projects.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">Lihat semua →</a>
            </div>
            <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach ($featuredProjects as $project)
                    <a href="{{ route('projects.show', $project->slug) }}" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
                        <div class="flex h-40 items-center justify-center bg-slate-200 text-4xl">
                            @if ($project->cover_image)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($project->cover_image) }}" alt="{{ $project->name }}" class="h-full w-full object-cover">
                            @else
                                🏢
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-slate-900">{{ $project->name }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $project->location ?: $project->client_name }}</p>
                            <div class="mt-3 flex items-center justify-between text-sm">
                                <span class="rounded-full bg-{{ $project->status->value === 'completed' ? 'green' : 'blue' }}-100 px-3 py-1 text-xs font-semibold text-{{ $project->status->value === 'completed' ? 'green' : 'blue' }}-700">{{ $project->status->label() }}</span>
                                <span class="font-semibold text-slate-800">{{ $project->valueFormatted }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Berita --}}
    @if ($latestPosts->isNotEmpty())
        <section class="bg-slate-50 py-20">
            <div class="mx-auto max-w-7xl px-6">
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-sm font-semibold text-blue-600">BERITA</p>
                        <h2 class="mt-2 text-3xl font-bold text-slate-900">Kabar Terbaru</h2>
                    </div>
                    <a href="{{ route('posts.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">Lihat semua →</a>
                </div>
                <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-3">
                    @foreach ($latestPosts as $post)
                        <a href="{{ route('posts.show', $post->slug) }}" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
                            <div class="flex h-40 items-center justify-center bg-slate-200 text-4xl">
                                @if ($post->cover_image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->cover_image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
                                @else
                                    📰
                                @endif
                            </div>
                            <div class="p-5">
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">{{ $post->category->label() }}</span>
                                <h3 class="mt-3 font-semibold leading-snug text-slate-900">{{ $post->title }}</h3>
                                <p class="mt-2 text-xs text-slate-500">{{ $post->published_at->format('d M Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- HSE / K3 --}}
    <section id="k3" class="bg-blue-950 py-20 text-white">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-1 gap-12 md:grid-cols-2">
                <div>
                    <p class="text-sm font-semibold text-blue-300">HSE / K3</p>
                    <h2 class="mt-2 text-3xl font-bold">{{ \App\Models\CompanyProfile::value('hse.title', 'Keselamatan adalah Prioritas Mutlak') }}</h2>
                    <p class="mt-4 leading-relaxed text-slate-300">
                        {{ \App\Models\CompanyProfile::value('hse.body') }}
                    </p>
                    <div class="mt-6 grid grid-cols-2 gap-4">
                        @forelse ($hseStats as $stat)
                            <div class="rounded-xl border border-white/10 p-4">
                                <p class="text-2xl font-extrabold text-blue-300">{{ $stat['value'] ?? '-' }}</p>
                                <p class="text-sm text-slate-400">{{ $stat['label'] ?? '' }}</p>
                            </div>
                        @empty
                            <div class="rounded-xl border border-white/10 p-4">
                                <p class="text-2xl font-extrabold text-blue-300">0</p>
                                <p class="text-sm text-slate-400">Kecelakaan Fatal</p>
                            </div>
                            <div class="rounded-xl border border-white/10 p-4">
                                <p class="text-2xl font-extrabold text-blue-300">5JT+</p>
                                <p class="text-sm text-slate-400">Jam Kerja Selamat</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="flex items-center justify-center rounded-2xl bg-white/5 p-10">
                    <p class="text-center text-4xl">🦺 <span class="block mt-4 text-sm text-slate-400">{{ \App\Models\CompanyProfile::value('hse.cert_label', 'Sertifikasi K3 Umum & Ahli K3 Konstruksi') }}</span></p>
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
                    <p>📍 {{ \App\Models\CompanyProfile::value('contact.address') }}</p>
                    <p>📞 {{ \App\Models\CompanyProfile::value('contact.phone') }}</p>
                    <p>✉️ {{ \App\Models\CompanyProfile::value('contact.email') }}</p>
                </div>
            </div>
            <form action="{{ route('contact.store') }}" method="POST" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                @csrf
                @if (session('success'))
                    <p class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-700">{{ session('success') }}</p>
                @endif
                @if (session('error'))
                    <p class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm font-medium text-red-700">{{ session('error') }}</p>
                @endif
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <input name="name" type="text" placeholder="Nama" required value="{{ old('name') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
                    <input name="email" type="email" placeholder="Email" required value="{{ old('email') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
                </div>
                <input name="phone" type="tel" placeholder="No. HP" value="{{ old('phone') }}" class="mt-4 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
                <input name="subject" type="text" placeholder="Perihal" required value="{{ old('subject') }}" class="mt-4 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
                <textarea name="message" placeholder="Pesan" required rows="4" class="mt-4 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">{{ old('message') }}</textarea>
                <button type="submit" class="mt-5 w-full rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">Kirim Pesan</button>
            </form>
        </div>
    </section>
@endsection
