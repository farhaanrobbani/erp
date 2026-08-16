@extends('layouts.public')

@section('title', 'Berita — Karya Nusantara')
@section('metaDescription', 'Berita, artikel, dan pengumuman terbaru dari PT Karya Nusantara Konstruksi.')

@section('content')

    <section class="bg-gradient-to-br from-blue-900 via-blue-950 to-blue-950 pt-32 pb-16 text-white">
        <div class="mx-auto max-w-7xl px-6">
            <p class="text-sm font-semibold text-blue-300">BERITA & ARTIKEL</p>
            <h1 class="mt-2 text-4xl font-extrabold md:text-5xl">Kabar Terbaru</h1>
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-8 flex flex-wrap gap-3">
                <a href="{{ route('posts.index') }}" class="rounded-full border px-4 py-1.5 text-sm font-medium {{ ! request('category') ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300 text-slate-600 hover:border-blue-500 hover:text-blue-600' }}">Semua</a>
                @foreach ($categories as $category)
                    <a href="{{ route('posts.index', ['category' => $category->value]) }}" class="rounded-full border px-4 py-1.5 text-sm font-medium {{ request('category') === $category->value ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300 text-slate-600 hover:border-blue-500 hover:text-blue-600' }}">{{ $category->label() }}</a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($posts as $post)
                    <a href="{{ route('posts.show', $post->slug) }}" class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
                        <div class="flex h-44 items-center justify-center overflow-hidden bg-slate-200 text-4xl">
                            @if ($post->cover_image)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->cover_image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition group-hover:scale-105">
                            @else
                                📰
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">{{ $post->category->label() }}</span>
                                <span class="text-xs text-slate-500">{{ $post->published_at->format('d M Y') }}</span>
                            </div>
                            <h2 class="mt-3 font-semibold leading-snug text-slate-900 group-hover:text-blue-600">{{ $post->title }}</h2>
                            <p class="mt-2 line-clamp-3 text-sm text-slate-500">{{ $post->excerpt }}</p>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full py-12 text-center text-slate-500">Belum ada berita yang dipublikasikan.</p>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $posts->links() }}
            </div>
        </div>
    </section>
@endsection
