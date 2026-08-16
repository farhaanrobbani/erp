@extends('layouts.public')

@section('title', 'Portofolio Proyek — Karya Nusantara')
@section('metaDescription', 'Daftar proyek konstruksi, sipil, dan MEP yang telah dan sedang kami kerjakan untuk klien BUMN, pemerintah, dan swasta.')

@section('content')

    <section class="bg-gradient-to-br from-blue-900 via-blue-950 to-blue-950 pt-32 pb-16 text-white">
        <div class="mx-auto max-w-7xl px-6">
            <p class="text-sm font-semibold text-blue-300">PORTOFOLIO</p>
            <h1 class="mt-2 text-4xl font-extrabold md:text-5xl">Proyek Kami</h1>
            <p class="mt-4 max-w-2xl text-slate-300">Berbagai proyek strategis yang telah kami selesaikan maupun yang sedang berjalan.</p>
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-8 flex flex-wrap gap-3">
                <a href="{{ route('projects.index') }}" class="rounded-full border px-4 py-1.5 text-sm font-medium {{ ! request('category') ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300 text-slate-600 hover:border-blue-500 hover:text-blue-600' }}">Semua</a>
                @foreach ($categories as $category)
                    <a href="{{ route('projects.index', ['category' => $category->value]) }}" class="rounded-full border px-4 py-1.5 text-sm font-medium {{ request('category') === $category->value ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300 text-slate-600 hover:border-blue-500 hover:text-blue-600' }}">{{ $category->label() }}</a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($projects as $project)
                    <a href="{{ route('projects.show', $project->slug) }}" class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
                        <div class="flex h-48 items-center justify-center overflow-hidden bg-slate-200 text-4xl">
                            @if ($project->cover_image)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($project->cover_image) }}" alt="{{ $project->name }}" class="h-full w-full object-cover transition group-hover:scale-105">
                            @else
                                🏢
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">{{ $project->category->label() }}</span>
                                <span class="rounded-full bg-{{ $project->status->value === 'completed' ? 'green' : 'blue' }}-100 px-3 py-1 text-xs font-semibold text-{{ $project->status->value === 'completed' ? 'green' : 'blue' }}-700">{{ $project->status->label() }}</span>
                            </div>
                            <h2 class="mt-3 font-semibold text-slate-900 group-hover:text-blue-600">{{ $project->name }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ $project->client_name }} — {{ $project->location ?: '-' }}</p>
                            <p class="mt-3 text-sm font-semibold text-slate-800">{{ $project->valueFormatted }}</p>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full py-12 text-center text-slate-500">Belum ada proyek yang dipublikasikan.</p>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $projects->links() }}
            </div>
        </div>
    </section>
@endsection
