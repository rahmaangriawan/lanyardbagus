@php
    $footerData = data_get($settings, 'homepage_data.footer', []);
    $navLinks = data_get($footerData, 'navigation_links', [
        ['label' => 'Beranda', 'href' => '/'],
        ['label' => 'Tentang Kami', 'href' => '/tentang'],
        ['label' => 'Jenis Material', 'href' => '/jenis'],
    ]);
    $insightsLinks = data_get($footerData, 'insights_links', [
        ['label' => 'Semua Artikel', 'href' => '/blog'],
    ]);
    $layananLinks = data_get($footerData, 'layanan_links', [
        ['label' => 'Kontak Kami', 'href' => '/kontak'],
        ['label' => 'Info Pengiriman', 'href' => '/pengiriman'],
        ['label' => 'Kebijakan & Syarat', 'href' => '/kebijakan'],
    ]);
@endphp

<!-- Global Footer -->
<footer class="bg-white text-cb-ink pt-20 pb-10 border-t border-cb-hairline">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">

            <!-- Brand Section -->
            <div class="space-y-6">
                @if(isset($settings) && $settings->logoImage)
                    <img src="{{ $settings->logoImage->url }}" alt="{{ $settings->site_title }}" class="h-8 w-auto">
                @else
                    <span
                        class="text-xl font-bold tracking-tight text-cb-ink">{{ $settings->site_title ?? 'Lanyardbagus' }}</span>
                @endif
                <p class="text-cb-body text-sm leading-relaxed max-w-xs">
                    Solusi identitas perusahaan terbaik di Indonesia. Kami menyediakan lanyard premium, kartu identitas,
                    dan perlengkapan branding berkualitas tinggi.
                </p>
            </div>

            <!-- Navigation -->
            <div>
                <h4 class="text-cb-ink font-bold text-sm uppercase tracking-widest mb-6">{{ data_get($footerData, 'navigation_title', 'Navigasi') }}</h4>
                <ul class="space-y-4">
                    @foreach($navLinks as $link)
                        <li><a href="{{ data_get($link, 'href', '#') }}" class="text-cb-body text-sm hover:text-cb-blue transition-colors">{{ data_get($link, 'label', 'Menu') }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Blog Categories / Insights -->
            <div>
                <h4 class="text-cb-ink font-bold text-sm uppercase tracking-widest mb-6">{{ data_get($footerData, 'insights_title', 'Insights') }}</h4>
                <ul class="space-y-4">
                    @foreach($insightsLinks as $link)
                        <li><a href="{{ data_get($link, 'href', '#') }}" class="text-cb-body text-sm hover:text-cb-blue transition-colors">{{ data_get($link, 'label', 'Menu') }}</a></li>
                    @endforeach
                    
                    {{-- Keep some dynamic categories if needed, but the user wants it configurable --}}
                    @if(empty($insightsLinks))
                        @php $footerCats = \App\Models\Category::take(3)->get(); @endphp
                        @foreach($footerCats as $fcat)
                            <li><a href="/blog/category/{{ $fcat->slug }}"
                                    class="text-cb-body text-sm hover:text-cb-blue transition-colors">{{ $fcat->name }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <!-- Layanan -->
            <div>
                <h4 class="text-cb-ink font-bold text-sm uppercase tracking-widest mb-6">{{ data_get($footerData, 'layanan_title', 'Layanan') }}</h4>
                <ul class="space-y-4">
                    @foreach($layananLinks as $link)
                        <li><a href="{{ data_get($link, 'href', '#') }}" class="text-cb-body text-sm hover:text-cb-blue transition-colors">{{ data_get($link, 'label', 'Menu') }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="pt-8 border-t border-cb-hairline flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-cb-muted text-sm">
                &copy; {{ date('Y') }} {{ $settings->site_title ?? 'Lanyardbagus' }}. {{ data_get($footerData, 'copyright_text', 'Dibuat dengan dedikasi untuk branding identitas.') }}
            </p>
            <div class="flex items-center gap-6">
                <a href="/kebijakan" class="text-cb-muted text-sm hover:text-cb-blue transition-colors">Kebijakan & Syarat</a>
                <a href="/kontak" class="text-cb-muted text-sm hover:text-cb-blue transition-colors">Hubungi Kami</a>
            </div>
        </div>
    </div>
</footer>