<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>@yield('title', $settings->site_title ?? 'Lanyardbagus')</title>
    <meta name="description" content="@yield('meta_description', $settings->homepage_meta_description ?? '')">
    <link rel="canonical" href="{{ url()->current() }}">
    
    @if(isset($settings) && $settings->faviconImage)
    <link rel="icon" type="image/x-icon" href="{{ $settings->faviconImage->url }}">
    @endif

    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap">
    </noscript>

    <!-- Tailwind CSS with Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html { scroll-behavior: smooth; }
        body { overflow-x: clip; }
        .display-mega { font-size: 80px; font-weight: 400; line-height: 1.0; letter-spacing: -2px; }
        .display-xl  { font-size: 64px; font-weight: 400; line-height: 1.0; letter-spacing: -1.6px; }
        .display-lg  { font-size: 52px; font-weight: 400; line-height: 1.0; letter-spacing: -1.3px; }
        .display-md  { font-size: 44px; font-weight: 400; line-height: 1.09; letter-spacing: -1px; }
        .display-sm  { font-size: 36px; font-weight: 400; line-height: 1.11; letter-spacing: -0.5px; }
        .title-lg    { font-size: 32px; font-weight: 400; line-height: 1.13; letter-spacing: -0.4px; }
        @media (max-width: 1024px) { .display-mega { font-size: 64px; letter-spacing: -1.6px; } }
        @media (max-width: 640px)  { .display-mega { font-size: 40px; letter-spacing: -1px; } .display-lg { font-size: 36px; } .display-md { font-size: 32px; } .display-sm { font-size: 28px; } }
        .nav-glass { background: rgba(255,255,255,0.92); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        details summary::-webkit-details-marker { display: none; }
    </style>
    @stack('styles')
    @stack('head')
</head>
<body class="bg-cb-canvas text-cb-ink font-sans antialiased">
    @php
        if (!isset($settings) || !$settings) {
            $settings = \App\Models\SiteSetting::with(['logoImage', 'faviconImage'])->first();
        }
        $homepage = optional($settings)->homepage_data ?? [];
        $navLinks = data_get($homepage, 'navigation_links', [
            ['label' => 'Beranda', 'href' => '/#beranda'],
            ['label' => 'Cerita Kami', 'href' => '/#cerita'],
            ['label' => 'Produk', 'href' => '/#produk'],
            ['label' => 'Harga', 'href' => '/#harga'],
            ['label' => 'FAQ', 'href' => '/faq'],
        ]);
        $headerCta = data_get($homepage, 'header_cta', [
            'label' => 'Pesan Sekarang',
            'href' => '/#hubungi',
        ]);
    @endphp

    <!-- Global Top Navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 nav-glass border-b border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center">
                <a href="/">
                    @if(isset($settings) && $settings->logoImage)
                    <img src="{{ $settings->logoImage->url }}" alt="{{ $settings->site_title ?? 'Logo' }}" class="h-8 w-auto" width="284" height="56" decoding="async">
                    @else
                    <span class="text-lg font-semibold text-cb-ink tracking-tight">{{ $settings->site_title ?? 'Lanyardbagus' }}</span>
                    @endif
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-6">
                @foreach($navLinks as $link)
                    @php
                        $href = data_get($link, 'href', '#');
                        if (str_starts_with($href, '#')) {
                            $href = '/' . $href;
                        }
                    @endphp
                    <a href="{{ $href }}" class="text-sm font-medium text-cb-body hover:text-cb-ink transition-colors">
                        {{ data_get($link, 'label', 'Menu') }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-3">
                @php
                    $ctaHref = data_get($headerCta, 'href', '/#hubungi');
                    if (str_starts_with($ctaHref, '#')) {
                        $ctaHref = '/' . $ctaHref;
                    }
                @endphp
                <!-- Desktop CTA -->
                <a href="{{ $ctaHref }}" class="!hidden md:!inline-flex bg-cb-blue text-white px-5 py-2.5 rounded-cb-pill text-sm font-semibold hover:bg-cb-blue-active transition-colors">
                    {{ data_get($headerCta, 'label', 'Pesan Sekarang') }}
                </a>

                <!-- Mobile Hamburger -->
                <button id="mobile-menu-btn" class="md:hidden flex p-2 text-cb-ink transition-transform duration-300" aria-label="Menu">
                    <svg id="menu-icon-hamburger" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="menu-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>
    
    <!-- Mobile Menu Backdrop -->
    <div id="mobile-menu-backdrop" class="fixed inset-0 z-[55] bg-cb-ink/20 backdrop-blur-sm hidden animate-in fade-in duration-300"></div>

    <!-- Mobile Menu Overlay (Dropdown Style) -->
    <div id="mobile-menu-overlay" class="fixed top-16 left-0 right-0 z-[60] bg-white hidden flex-col shadow-2xl border-b border-cb-hairline animate-in slide-in-from-top duration-300">
        <div class="overflow-y-auto px-6 py-8 flex flex-col gap-8">
            <nav class="flex flex-col gap-6">
                @foreach($navLinks as $link)
                    @php
                        $href = data_get($link, 'href', '#');
                        if (str_starts_with($href, '#')) {
                            $href = '/' . $href;
                        }
                    @endphp
                    <a href="{{ $href }}" class="text-xl font-normal text-cb-ink mobile-menu-link">
                        {{ data_get($link, 'label', 'Menu') }}
                    </a>
                @endforeach
            </nav>
            <div class="pt-8 border-t border-cb-hairline">
                <a href="{{ $ctaHref }}" class="w-full bg-cb-blue text-white py-4 rounded-cb-lg text-center font-normal block shadow-lg shadow-cb-blue/20 mobile-menu-link">
                    {{ data_get($headerCta, 'label', 'Pesan Sekarang') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('partials.footer')

    <script>
        // Mobile Menu Logic
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const hamburgerIcon = document.getElementById('menu-icon-hamburger');
        const closeIcon = document.getElementById('menu-icon-close');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
        const mobileMenuLinks = document.querySelectorAll('.mobile-menu-link');

        function toggleMenu() {
            const isOpen = !mobileMenuOverlay.classList.contains('hidden');
            
            if (isOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        }

        function openMenu() {
            mobileMenuOverlay.classList.remove('hidden');
            mobileMenuOverlay.classList.add('flex');
            mobileMenuBackdrop.classList.remove('hidden');
            hamburgerIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeMenu() {
            mobileMenuOverlay.classList.add('hidden');
            mobileMenuOverlay.classList.remove('flex');
            mobileMenuBackdrop.classList.add('hidden');
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleMenu);
        if (mobileMenuBackdrop) mobileMenuBackdrop.addEventListener('click', closeMenu);
        
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', closeMenu);
        });
    </script>
    @stack('scripts')

    <!-- Lucide Icons (CDN) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        function initLucide() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        document.addEventListener('DOMContentLoaded', initLucide);
        if (document.readyState !== 'loading') initLucide();
        // Global observer for dynamic content
        window.addEventListener('load', initLucide);
    </script>
</body>
</html>
