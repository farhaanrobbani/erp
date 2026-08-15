<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $metaDescription ?? 'Perusahaan kontraktor nasional terpercaya untuk proyek konstruksi, sipil, dan MEP.' }}">
    <title>{{ $title ?? 'PT. Karya Nusantara Konstruksi' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-800 antialiased">
    @include('partials.public-header')

    <main>
        @yield('content')
    </main>

    @include('partials.public-footer')
</body>
</html>
