@extends('layouts.public')

@section('title', $post->title . ' — Karya Nusantara')
@section('metaDescription', $post->excerpt)

@section('content')

    <section class="bg-gradient-to-br from-blue-900 via-blue-950 to-blue-950 pt-32 pb-16 text-white">
        <div class="mx-auto max-w-4xl px-6">
            <nav class="text-sm text-slate-400">
                <a href="{{ route('posts.index') }}" class="hover:text-white">Berita</a>
                <span class="mx-2">/</span>
                <span>{{ $post->category->label() }}</span>
            </nav>
            <h1 class="mt-3 text-3xl font-extrabold md:text-5xl">{{ $post->title }}</h1>
            <p class="mt-4 text-sm text-slate-300">
                {{ $post->published_at->format('d F Y') }}{{ $post->author ? ' — oleh ' . $post->author->name : '' }}
            </p>
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto max-w-4xl px-6">
            @if ($post->cover_image)
                <div class="overflow-hidden rounded-2xl">
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->cover_image) }}" alt="{{ $post->title }}" class="h-80 w-full object-cover">
                </div>
            @endif

            @if ($post->excerpt)
                <p class="mt-8 text-lg font-medium leading-relaxed text-slate-700">{{ $post->excerpt }}</p>
            @endif

            <div class="prose prose-slate mt-6 max-w-none">{!! $post->body !!}</div>

            <a href="{{ route('posts.index') }}" class="mt-10 inline-block rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">← Kembali ke Berita</a>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="border-t border-slate-100 bg-slate-50 py-16">
            <div class="mx-auto max-w-4xl px-6">
                <h2 class="text-2xl font-bold text-slate-900">Berita Lainnya</h2>
                <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                    @foreach ($related as $item)
                        <a href="{{ route('posts.show', $item->slug) }}" class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
                            <div class="flex h-32 items-center justify-center bg-slate-200 text-3xl">
                                @if ($item->cover_image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->cover_image) }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                                @else
                                    📰
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-semibold leading-snug text-slate-900 group-hover:text-blue-600">{{ $item->title }}</h3>
                                <p class="mt-1 text-xs text-slate-500">{{ $item->published_at->format('d M Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
