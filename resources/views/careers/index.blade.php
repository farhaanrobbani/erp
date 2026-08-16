@extends('layouts.public')

@section('title', 'Karier — Karya Nusantara')
@section('metaDescription', 'Lowongan pekerjaan terbaru di PT Karya Nusantara Konstruksi. Bergabunglah dengan tim profesional kami.')

@section('content')

    <section class="bg-gradient-to-br from-blue-900 via-blue-950 to-blue-950 pt-32 pb-16 text-white">
        <div class="mx-auto max-w-7xl px-6">
            <p class="text-sm font-semibold text-blue-300">KARIER</p>
            <h1 class="mt-2 text-4xl font-extrabold md:text-5xl">Bergabung Bersama Kami</h1>
            <p class="mt-4 max-w-2xl text-slate-300">Temukan peluang karier di proyek-proyek strategis nasional bersama tim profesional kami.</p>
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-12 px-6 lg:grid-cols-2">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Lowongan Terbuka</h2>

                @if (session('success'))
                    <p class="mt-4 rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-700">{{ session('success') }}</p>
                @endif
                @if (session('error'))
                    <p class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm font-medium text-red-700">{{ session('error') }}</p>
                @endif

                <div class="mt-6 space-y-4">
                    @forelse ($vacancies as $vacancy)
                        <details class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" {{ $loop->first ? 'open' : '' }}>
                            <summary class="flex cursor-pointer items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-slate-900">{{ $vacancy->title }}</h3>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $vacancy->department?->name ?? 'Umum' }} • {{ $vacancy->type->label() }} • {{ $vacancy->location ?: 'Seluruh Indonesia' }}
                                    </p>
                                </div>
                                @if ($vacancy->deadline)
                                    <span class="ml-4 shrink-0 text-xs text-slate-500">Batas: {{ $vacancy->deadline->format('d M Y') }}</span>
                                @endif
                            </summary>
                            <div class="mt-4 border-t border-slate-100 pt-4 text-sm leading-relaxed text-slate-600">
                                <h4 class="font-semibold text-slate-800">Deskripsi:</h4>
                                <div class="mt-1">{!! $vacancy->description ?? '-' !!}</div>
                                <h4 class="mt-3 font-semibold text-slate-800">Persyaratan:</h4>
                                <div class="mt-1">{!! $vacancy->requirements ?? '-' !!}</div>
                            </div>
                        </details>
                    @empty
                        <p class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-500">Saat ini belum ada lowongan yang dibuka. Silakan pantau kembali.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-slate-900">Kirim Lamaran</h2>
                <form action="{{ route('careers.apply') }}" method="POST" enctype="multipart/form-data" class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    @csrf
                    @if ($errors->any())
                        <div class="mb-4 space-y-1 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <label class="block text-sm font-medium text-slate-700">Posisi yang Dilamar *</label>
                    <select name="job_vacancy_id" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
                        <option value="">— Pilih posisi —</option>
                        @foreach ($vacancies as $vacancy)
                            <option value="{{ $vacancy->id }}" {{ old('job_vacancy_id') == $vacancy->id ? 'selected' : '' }}>{{ $vacancy->title }}</option>
                        @endforeach
                    </select>

                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nama Lengkap *</label>
                            <input name="name" type="text" required value="{{ old('name') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Email *</label>
                            <input name="email" type="email" required value="{{ old('email') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700">No. HP</label>
                        <input name="phone" type="tel" value="{{ old('phone') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700">Upload CV * <span class="font-normal text-slate-400">(PDF/DOC, maks 5MB)</span></label>
                        <input name="resume" type="file" required accept=".pdf,.doc,.docx" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700 focus:border-blue-500 focus:outline-none">
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700">Surat Lamaran (opsional)</label>
                        <textarea name="cover_letter" rows="4" placeholder="Tuliskan ringkasan pengalaman dan motivasi Anda..." class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">{{ old('cover_letter') }}</textarea>
                    </div>

                    <button type="submit" class="mt-5 w-full rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700" {{ $vacancies->isEmpty() ? 'disabled' : '' }}>Kirim Lamaran</button>
                </form>
            </div>
        </div>
    </section>
@endsection
