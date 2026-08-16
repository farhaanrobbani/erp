<header class="fixed inset-x-0 top-0 z-50 border-b border-white/10 bg-white/80 backdrop-blur">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
        <a href="/" class="flex items-center gap-2 font-bold text-slate-900">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-white">K</span>
            <span>Karya Nusantara</span>
        </a>
        <div class="hidden items-center gap-6 text-sm font-medium text-slate-600 md:flex">
            <a href="/#tentang" class="hover:text-blue-600">Tentang</a>
            <a href="/#layanan" class="hover:text-blue-600">Layanan</a>
            <a href="{{ route('projects.index') }}" class="hover:text-blue-600">Portofolio</a>
            <a href="/#k3" class="hover:text-blue-600">HSE / K3</a>
            <a href="{{ route('posts.index') }}" class="hover:text-blue-600">Berita</a>
            <a href="{{ route('careers.index') }}" class="hover:text-blue-600">Karier</a>
        </div>
        <div class="flex items-center gap-3">
            <a href="/admin" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Login</a>
            <a href="/#kontak" class="hidden rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 md:inline-block">Hubungi Kami</a>
        </div>
    </nav>
</header>
