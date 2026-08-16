<footer class="border-t border-slate-200 bg-blue-950 py-12 text-slate-400">
    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-8 px-6 md:grid-cols-3">
        <div>
            <p class="text-lg font-semibold text-white">{{ \App\Models\CompanyProfile::value('general.company_name', 'PT Karya Nusantara Konstruksi') }}</p>
            <p class="mt-2 text-sm">{{ \App\Models\CompanyProfile::value('general.slogan', 'Membangun Indonesia dengan kualitas, keselamatan, dan integritas.') }}</p>
        </div>
        <div>
            <p class="text-sm font-semibold text-white">Legalitas</p>
            <ul class="mt-2 space-y-1 text-sm">
                @foreach (\App\Models\CertificateLegality::where('is_active', true)->orderBy('type')->get() as $certificate)
                    <li>{{ $certificate->type->label() }}{{ $certificate->number ? ' — ' . $certificate->number : '' }}</li>
                @endforeach
            </ul>
        </div>
        <div>
            <p class="text-sm font-semibold text-white">Kontak</p>
            <ul class="mt-2 space-y-1 text-sm">
                <li>{{ \App\Models\CompanyProfile::value('contact.address') }}</li>
                <li>{{ \App\Models\CompanyProfile::value('contact.phone') }}</li>
                <li>{{ \App\Models\CompanyProfile::value('contact.email') }}</li>
            </ul>
        </div>
    </div>
    <p class="mt-8 text-center text-xs">© {{ date('Y') }} {{ \App\Models\CompanyProfile::value('general.company_name', 'PT Karya Nusantara Konstruksi') }}. Seluruh hak cipta dilindungi.</p>
</footer>
