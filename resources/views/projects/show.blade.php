@extends('layouts.public')

@section('title', $project->name . ' — Karya Nusantara')
@section('metaDescription', $project->excerpt ?? $project->name)

@section('content')

    <section class="bg-gradient-to-br from-blue-900 via-blue-950 to-blue-950 pt-32 pb-16 text-white">
        <div class="mx-auto max-w-7xl px-6">
            <nav class="text-sm text-slate-400">
                <a href="{{ route('projects.index') }}" class="hover:text-white">Portofolio</a>
                <span class="mx-2">/</span>
                <span>{{ $project->name }}</span>
            </nav>
            <h1 class="mt-3 text-4xl font-extrabold md:text-5xl">{{ $project->name }}</h1>
            <div class="mt-4 flex flex-wrap gap-3 text-sm">
                <span class="rounded-full bg-blue-500/15 px-3 py-1 font-semibold text-blue-300">{{ $project->category->label() }}</span>
                <span class="rounded-full bg-{{ $project->status->value === 'completed' ? 'green' : 'blue' }}-500/15 px-3 py-1 font-semibold text-{{ $project->status->value === 'completed' ? 'green' : 'blue' }}-200">{{ $project->status->label() }}</span>
                <span class="rounded-full bg-white/10 px-3 py-1 font-semibold text-slate-300">{{ $project->client_type->label() }}</span>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    @if ($project->cover_image)
                        <div class="overflow-hidden rounded-2xl">
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($project->cover_image) }}" alt="{{ $project->name }}" class="h-80 w-full object-cover">
                        </div>
                    @endif

                    <div class="mt-8">
                        <h2 class="text-2xl font-bold text-slate-900">Deskripsi Proyek</h2>
                        <div class="prose mt-4 max-w-none text-slate-600">{!! $project->description !!}</div>
                    </div>

                    @if ($project->galleries->isNotEmpty())
                        <div class="mt-10">
                            <h2 class="text-2xl font-bold text-slate-900">Galeri Proyek</h2>
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                @foreach ($project->galleries as $gallery)
                                    <figure class="overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($gallery->file_path) }}" alt="{{ $gallery->caption ?: $project->name }}" class="h-48 w-full object-cover">
                                        @if ($gallery->caption)
                                            <figcaption class="p-3 text-sm text-slate-500">{{ $gallery->caption }}</figcaption>
                                        @endif
                                    </figure>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <aside class="h-fit rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <h2 class="text-lg font-bold text-slate-900">Ringkasan Proyek</h2>
                    <dl class="mt-4 space-y-4 text-sm">
                        <div>
                            <dt class="text-slate-500">Klien</dt>
                            <dd class="mt-0.5 font-semibold text-slate-800">{{ $project->client_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Jenis Klien</dt>
                            <dd class="mt-0.5 font-semibold text-slate-800">{{ $project->client_type->label() }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Nilai Kontrak</dt>
                            <dd class="mt-0.5 font-semibold text-slate-800">{{ $project->valueFormatted }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Lokasi</dt>
                            <dd class="mt-0.5 font-semibold text-slate-800">{{ $project->location ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Periode</dt>
                            <dd class="mt-0.5 font-semibold text-slate-800">
                                @if ($project->start_date && $project->end_date)
                                    {{ $project->start_date->format('d M Y') }} — {{ $project->end_date->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                    </dl>
                </aside>
            </div>
        </div>
    </section>
@endsection
