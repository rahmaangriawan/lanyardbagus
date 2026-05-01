<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $homepageTitle = $settings->homepage_seo_title ?? '';
        $siteTitle = $settings->site_title ?? 'Lanyardbagus';
        $pageTitle = filled($homepageTitle) ? trim($homepageTitle . ' - ' . $siteTitle) : $siteTitle;
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $settings->homepage_meta_description ?? '' }}">
    <link rel="canonical" href="{{ url()->current() }}">
    @if($settings->faviconImage)
    <link rel="icon" type="image/x-icon" href="{{ $settings->faviconImage->url }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">

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
    <!-- Homepage Schemas -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "WebSite",
      "name": "{{ $settings->site_title ?? 'Lanyardbagus' }}",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@@type": "SearchAction",
        "target": "{{ url('/blog') }}?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "Organization",
      "name": "{{ $settings->site_title ?? 'Lanyardbagus' }}",
      "url": "{{ url('/') }}",
      "logo": "{{ $settings->logoImage ? $settings->logoImage->url : '' }}",
      "address": {
        "@@type": "PostalAddress",
        "addressCountry": "ID",
        "addressRegion": "Seluruh Indonesia"
      },
      "areaServed": "Indonesia",
      "contactPoint": {
        "@@type": "ContactPoint",
        "telephone": "{{ $settings->whatsapp_number ?? '' }}",
        "contactType": "customer service",
        "areaServed": "ID",
        "availableLanguage": "Indonesian"
      }
    }
    </script>
</head>
<body class="bg-cb-canvas text-cb-ink font-sans antialiased">
    @php
        $homepage = $settings->homepage_data ?? [];
        $navLinks = data_get($homepage, 'navigation_links', [
            ['label' => 'Beranda', 'href' => '#beranda'],
            ['label' => 'Cerita Kami', 'href' => '#cerita'],
            ['label' => 'Produk', 'href' => '#produk'],
            ['label' => 'Harga', 'href' => '#harga'],
            ['label' => 'FAQ', 'href' => '/faq'],
        ]);
        $headerCta = data_get($homepage, 'header_cta', [
            'label' => 'Pesan Sekarang',
            'href' => '#hubungi',
        ]);
        $hero = data_get($homepage, 'hero', []);
        $problem = data_get($homepage, 'problem', []);
        $problemItems = data_get($homepage, 'problem.items', [
            [
                'icon' => 'calendar-x',
                'title' => 'Acara Sudah Dekat, Tali Belum Jadi',
                'description' => 'Ketegangan saat vendor berjanji palsu dan membiarkan panitia cemas di hari-hari krusial menjelang acara besar Anda.',
            ],
            [
                'icon' => 'droplet',
                'title' => 'Warna Pudar yang Merusak Penampilan',
                'description' => 'Anda sudah susah payah mendesain logo perusahaan yang indah, namun hasil cetaknya buram dan luntur dalam hitungan minggu.',
            ],
            [
                'icon' => 'frown',
                'title' => 'Bahan Kaku dan Membuat Gatal',
                'description' => 'Memaksa tim Anda memakai lanyard berbahan kasar seharian, membuat mereka tidak nyaman saat melayani klien.',
            ],
        ]);
        $story = data_get($homepage, 'story', []);
        $storySteps = data_get($homepage, 'story.steps', [
            [
                'number' => '01',
                'title' => 'Material Pilihan yang Menghargai Kenyamanan Tim Anda',
                'description' => 'Kami percaya identitas yang baik harus terasa nyaman saat dikenakan.',
                'image_url' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'number' => '02',
                'title' => 'Warna Tajam yang Meneriakkan Karakter Brand Anda',
                'description' => 'Setiap detail warna diterjemahkan dengan presisi mutlak agar identitas Anda tetap kuat.',
                'image_url' => 'https://images.unsplash.com/photo-1614316315266-9e9ebdb1ce45?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'number' => '03',
                'title' => 'Tiba Tepat Waktu, Jauh Sebelum Momen Besar Dimulai',
                'description' => 'Sistem produksi kami dirancang untuk satu janji: selesai tepat waktu dan tanpa kompromi.',
                'image_url' => 'https://images.unsplash.com/photo-1587293852726-70cdb56c2866?auto=format&fit=crop&w=800&q=80',
            ],
        ]);
        $customize = data_get($homepage, 'customize', []);
        $customizeItems = data_get($homepage, 'customize.items', [
            [
                'icon' => 'printer',
                'image_url' => 'https://images.unsplash.com/photo-1628126235206-5260b9ea6441?auto=format&fit=crop&w=600&q=80',
                'title' => 'Printing Premium',
                'description' => 'Pilihan terpopuler. Mampu menampilkan gradasi warna dan desain kompleks tanpa batas dengan presisi luar biasa.',
                'href' => '#kontak',
            ],
            [
                'icon' => 'badge-check',
                'image_url' => '',
                'title' => 'Paket Identitas (Lanyard + ID Card)',
                'description' => 'Harmonisasi sempurna. Tali dan kartu identitas yang dirancang senada untuk menciptakan kesatuan visual yang solid.',
                'href' => '#kontak',
            ],
            [
                'icon' => 'layers',
                'image_url' => '',
                'title' => 'Lanyard 2 Sisi',
                'description' => 'Maksimalkan area desain Anda dengan cetakan bolak-balik. Sempurna untuk visibilitas brand ekstra dari segala sudut.',
                'href' => '#kontak',
            ],
        ]);
        $faqSection = data_get($homepage, 'faq', []);
        $faqItems = data_get($homepage, 'faq.items', [
            [
                'question' => 'Berapa minimum pemesanan lanyard custom?',
                'answer' => 'Kami menerima pemesanan dalam jumlah fleksibel. Untuk mendapatkan harga terbaik dan efisiensi produksi, kami merekomendasikan minimum order sebanyak 50 pcs. Namun, jangan ragu untuk berkonsultasi jika kebutuhan Anda di bawah jumlah tersebut.',
            ],
            [
                'question' => 'Berapa lama waktu pengerjaan produksi?',
                'answer' => 'Standar waktu produksi kami berkisar antara 3 hingga 7 hari kerja tergantung dari jumlah pesanan dan antrean produksi. Kami juga menyediakan layanan prioritas (express) untuk kebutuhan mendesak dengan biaya tambahan.',
            ],
            [
                'question' => 'Apakah saya bisa meminta dibuatkan desain?',
                'answer' => 'Tentu saja! Tim desain internal kami siap membantu memvisualisasikan ide Anda. Layanan desain dasar ini kami berikan secara gratis khusus untuk pelanggan yang telah melakukan pembayaran uang muka pesanan.',
            ],
            [
                'question' => 'Bahan apa yang paling direkomendasikan?',
                'answer' => 'Untuk hasil cetak paling tajam dengan kenyamanan maksimal, kami sangat menyarankan bahan Tissue (Polyester halus) dipadukan dengan teknik Printing Premium. Bahan ini sangat lembut di leher dan mampu menyerap tinta sublimasi dengan sempurna.',
            ],
        ]);
        $ctaBand = data_get($homepage, 'cta', []);
        $contact = data_get($homepage, 'contact', []);
        $contactSteps = data_get($homepage, 'contact.steps', [
            '1' => 'Data Diri',
            '2' => 'Kontak',
            '3' => 'Detail',
        ]);
        $contactLabels = data_get($homepage, 'contact.labels', [
            'name' => 'Nama Lengkap',
            'email' => 'Email',
            'whatsapp' => 'No. WhatsApp',
            'address' => 'Alamat Lengkap',
            'message' => 'Pesan',
            'file' => 'Upload Referensi Desain',
        ]);
        $contactPlaceholders = data_get($homepage, 'contact.placeholders', [
            'name' => 'Masukkan nama lengkap Anda',
            'email' => 'email@contoh.com',
            'whatsapp' => '08xx-xxxx-xxxx',
            'address' => 'Alamat pengiriman Anda...',
            'message' => 'Ceritakan kebutuhan lanyard Anda...',
        ]);
        $contactButtons = data_get($homepage, 'contact.buttons', [
            'next' => 'Lanjutkan',
            'back' => 'Kembali',
            'submit' => 'Kirim Pesanan',
            'file' => 'Klik atau seret gambar ke sini',
        ]);
        $contactHints = data_get($homepage, 'contact.hints', [
            'file' => 'PNG, JPG, WebP — Maks. 5MB',
        ]);
    @endphp

    <!-- Top Navigation — Light -->
    <header class="fixed top-0 left-0 right-0 z-50 nav-glass border-b border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center">
                <a href="/">
                    @if($settings && $settings->logoImage)
                    <img src="{{ $settings->logoImage->url }}" alt="{{ $settings->site_title ?? 'Logo' }}" class="h-8 w-auto">
                    @else
                    <span class="text-lg font-semibold text-cb-ink tracking-tight">{{ $settings->site_title ?? 'Lanyardbagus' }}</span>
                    @endif
                </a>
            </div>

            <nav class="hidden md:flex items-center gap-6">
                @foreach($navLinks as $link)
                    <a href="{{ data_get($link, 'href', '#') }}" class="text-sm font-medium text-cb-body hover:text-cb-ink transition-colors">
                        {{ data_get($link, 'label', 'Menu') }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-3">
                <!-- Desktop CTA -->
                <a href="{{ data_get($headerCta, 'href', '#hubungi') }}" class="!hidden md:!inline-flex bg-cb-blue text-white px-5 py-2.5 rounded-cb-pill text-sm font-semibold hover:bg-cb-blue-active transition-colors">
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
                    <a href="{{ data_get($link, 'href', '#') }}" class="text-xl font-normal text-cb-ink mobile-menu-link">
                        {{ data_get($link, 'label', 'Menu') }}
                    </a>
                @endforeach
            </nav>
            <div class="pt-8 border-t border-cb-hairline">
                <a href="{{ data_get($headerCta, 'href', '#hubungi') }}" class="w-full bg-cb-blue text-white py-4 rounded-cb-lg text-center font-normal block shadow-lg shadow-cb-blue/20 mobile-menu-link">
                    {{ data_get($headerCta, 'label', 'Pesan Sekarang') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Hero Band — Dark with Background Image + Animated Overlay -->
    <section id="beranda" class="relative bg-cb-surface-dark min-h-[100vh] flex items-center overflow-hidden">
        <!-- Full-bleed background image -->
        <div class="absolute inset-0 z-0">
            <img src="{{ data_get($hero, 'image_url', '/storage/media/hero-lanyard-5bc7f4.webp') }}" alt="" class="w-full h-full object-cover" aria-hidden="true">
        </div>
        <!-- Gradient overlays for text readability -->
        <div class="absolute inset-0 z-[1] bg-gradient-to-r from-cb-surface-dark via-cb-surface-dark/85 to-cb-surface-dark/40"></div>
        <div class="absolute inset-0 z-[1] bg-gradient-to-t from-cb-surface-dark via-transparent to-cb-surface-dark/60"></div>
        <!-- Animated gradient orbs (on top of gradient) -->
        <div class="hero-bg-wrap absolute inset-0 z-[2]">
            <div class="hero-orb hero-orb-1"></div>
            <div class="hero-orb hero-orb-2"></div>
            <div class="hero-orb hero-orb-3"></div>
            <div class="hero-orb hero-orb-4"></div>
        </div>
        <!-- Noise texture for depth -->
        <div class="absolute inset-0 z-[3] opacity-[0.03]" style="background-image:url('data:image/svg+xml,%3Csvg viewBox=%220 0 256 256%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22n%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23n)%22/%3E%3C/svg%3E');"></div>

        <style>
            .hero-bg-wrap { filter: blur(80px); }
            .hero-orb {
                position: absolute;
                border-radius: 9999px;
                will-change: transform, opacity;
            }
            .hero-orb-1 {
                width: 600px; height: 600px;
                background: radial-gradient(circle, rgba(0,82,255,0.35) 0%, transparent 70%);
                top: -10%; left: -5%;
                animation: orb-drift-1 12s ease-in-out infinite alternate;
            }
            .hero-orb-2 {
                width: 500px; height: 500px;
                background: radial-gradient(circle, rgba(0,82,255,0.2) 0%, transparent 70%);
                bottom: -15%; right: -10%;
                animation: orb-drift-2 14s ease-in-out infinite alternate;
            }
            .hero-orb-3 {
                width: 350px; height: 350px;
                background: radial-gradient(circle, rgba(5,177,105,0.15) 0%, transparent 70%);
                top: 40%; left: 50%;
                animation: orb-drift-3 16s ease-in-out infinite alternate;
            }
            .hero-orb-4 {
                width: 450px; height: 450px;
                background: radial-gradient(circle, rgba(0,62,204,0.25) 0%, transparent 70%);
                top: 10%; right: 20%;
                animation: orb-drift-4 18s ease-in-out infinite alternate;
            }
            @keyframes orb-drift-1 {
                0%   { transform: translate(0, 0) scale(1); opacity: 0.7; }
                100% { transform: translate(80px, 60px) scale(1.15); opacity: 1; }
            }
            @keyframes orb-drift-2 {
                0%   { transform: translate(0, 0) scale(1); opacity: 0.6; }
                100% { transform: translate(-60px, -80px) scale(1.2); opacity: 0.9; }
            }
            @keyframes orb-drift-3 {
                0%   { transform: translate(0, 0) scale(1); opacity: 0.5; }
                100% { transform: translate(-100px, 40px) scale(1.1); opacity: 0.8; }
            }
            @keyframes orb-drift-4 {
                0%   { transform: translate(0, 0) scale(1); opacity: 0.4; }
                100% { transform: translate(50px, -50px) scale(1.25); opacity: 0.7; }
            }
        </style>

        <div class="max-w-[1200px] mx-auto px-6 relative z-[10] pt-32 pb-section w-full">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <!-- Left — Text & CTAs -->
                <div class="flex-1 flex flex-col items-start gap-6">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-cb-pill bg-white/5 border border-white/10 text-cb-muted-soft text-sm font-semibold tracking-wider uppercase backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-cb-blue animate-pulse"></span>
                        {{ data_get($hero, 'badge', 'TALI BERKUALITAS PREMIUM') }}
                    </span>

                    <h1 class="display-mega text-white">
                        {{ data_get($hero, 'title', 'Tali Berkualitas, Wujud Nyata Identitas Tim Anda.') }}
                    </h1>

                    <p class="text-base text-cb-muted-soft leading-relaxed max-w-xl">
                        {{ data_get($hero, 'description', 'Bayangkan seluruh tim Anda berjalan dengan penuh kebanggaan, mengenakan lanyard yang mewakili profesionalisme dan visi besar perusahaan. Lanyardbagus merancang setiap detail jahitan untuk menceritakan kisah brand Anda.') }}
                    </p>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ data_get($hero, 'button_url', '#cerita') }}" class="inline-flex items-center justify-center bg-cb-blue text-white px-8 py-3.5 rounded-cb-pill font-semibold text-base hover:bg-cb-blue-active transition-colors shadow-[0_0_24px_rgba(0,82,255,0.3)]">
                            {{ data_get($hero, 'button_text', 'Lihat Cerita Kami') }}
                        </a>
                        <a href="{{ data_get($hero, 'secondary_button_url', '#hubungi') }}" class="inline-flex items-center justify-center bg-white/5 text-white border border-white/15 px-8 py-3.5 rounded-cb-pill font-semibold text-base hover:bg-white/10 hover:border-white/25 transition-all backdrop-blur-sm">
                            {{ data_get($hero, 'secondary_button_text', 'Konsultasi Desain') }}
                        </a>
                    </div>
                </div>

                <!-- Right — 3D Tilt Card -->
                <div class="flex-1 relative w-full flex justify-center lg:justify-end">
                    <div class="tilt-wrap" id="tilt-wrap">
                        <div class="tilt-card" id="tilt-card">
                            <img src="{{ data_get($hero, 'card_image_url', data_get($hero, 'image_url', '/storage/media/hero-lanyard-5bc7f4.webp')) }}" alt="{{ data_get($hero, 'title', 'Lanyard Premium') }}" class="tilt-img">
                            <div class="tilt-glare" id="tilt-glare"></div>
                            <div class="tilt-badge">
                                <div class="tilt-badge-dot"></div>
                                <span>{{ data_get($hero, 'card_badge', 'Premium Quality') }}</span>
                            </div>
                        </div>
                        <!-- Floating reflection shadow -->
                        <div class="tilt-shadow" id="tilt-shadow"></div>
                    </div>
                    <style>
                        .tilt-wrap {
                            position: relative;
                            width: 340px; height: 440px;
                            perspective: 1000px;
                        }
                        @media (min-width: 1024px) { .tilt-wrap { width: 380px; height: 480px; } }
                        @media (max-width: 640px) { .tilt-wrap { width: 280px; height: 370px; } }
                        .tilt-card {
                            position: relative;
                            width: 100%; height: 100%;
                            border-radius: 24px;
                            overflow: hidden;
                            border: 1px solid rgba(255,255,255,0.15);
                            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05) inset;
                            transform-style: preserve-3d;
                            transition: transform 0.15s ease-out, box-shadow 0.3s ease;
                            will-change: transform;
                            animation: tilt-idle 6s ease-in-out infinite;
                        }
                        .tilt-card:hover { animation: none; }
                        @keyframes tilt-idle {
                            0%, 100% { transform: rotateX(2deg) rotateY(-2deg); }
                            25% { transform: rotateX(-1deg) rotateY(3deg); }
                            50% { transform: rotateX(-2deg) rotateY(-1deg); }
                            75% { transform: rotateX(1deg) rotateY(2deg); }
                        }
                        .tilt-img {
                            width: 100%; height: 100%;
                            object-fit: cover;
                            pointer-events: none;
                        }
                        .tilt-glare {
                            position: absolute; inset: 0;
                            background: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.25) 0%, transparent 60%);
                            opacity: 0;
                            transition: opacity 0.3s ease;
                            pointer-events: none;
                            mix-blend-mode: overlay;
                        }
                        .tilt-card:hover .tilt-glare { opacity: 1; }
                        .tilt-badge {
                            position: absolute;
                            bottom: 16px; left: 16px;
                            display: flex; align-items: center; gap: 8px;
                            background: rgba(10,11,13,0.75);
                            backdrop-filter: blur(16px);
                            -webkit-backdrop-filter: blur(16px);
                            border: 1px solid rgba(255,255,255,0.1);
                            border-radius: 100px;
                            padding: 8px 16px 8px 12px;
                            transform: translateZ(30px);
                        }
                        .tilt-badge-dot {
                            width: 8px; height: 8px;
                            border-radius: 50%;
                            background: #05b169;
                            box-shadow: 0 0 8px rgba(5,177,105,0.6);
                            animation: dot-pulse 2s ease-in-out infinite;
                        }
                        @keyframes dot-pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }
                        .tilt-badge span {
                            font-size: 14px; font-weight: 600;
                            color: rgba(255,255,255,0.9);
                            letter-spacing: 0.5px;
                        }
                        .tilt-shadow {
                            position: absolute;
                            bottom: -30px; left: 10%; right: 10%;
                            height: 40px;
                            background: radial-gradient(ellipse, rgba(0,82,255,0.15) 0%, transparent 70%);
                            border-radius: 50%;
                            filter: blur(12px);
                            transition: transform 0.15s ease-out, opacity 0.3s ease;
                        }
                    </style>
                    <script>
                    (function(){
                        const wrap = document.getElementById('tilt-wrap');
                        const card = document.getElementById('tilt-card');
                        const glare = document.getElementById('tilt-glare');
                        const shadow = document.getElementById('tilt-shadow');
                        if (!wrap || !card) return;
                        wrap.addEventListener('mousemove', (e) => {
                            const r = wrap.getBoundingClientRect();
                            const x = (e.clientX - r.left) / r.width;
                            const y = (e.clientY - r.top) / r.height;
                            const rotY = (x - 0.5) * 24;
                            const rotX = (0.5 - y) * 24;
                            card.style.transform = `rotateX(${rotX}deg) rotateY(${rotY}deg)`;
                            if (glare) glare.style.background = `radial-gradient(circle at ${x*100}% ${y*100}%, rgba(255,255,255,0.3) 0%, transparent 60%)`;
                            if (shadow) shadow.style.transform = `translateX(${(x-0.5)*20}px) scaleX(${1-Math.abs(x-0.5)*0.3})`;
                        });
                        wrap.addEventListener('mouseleave', () => {
                            card.style.transform = '';
                            if (glare) glare.style.opacity = '0';
                            if (shadow) shadow.style.transform = '';
                        });
                        wrap.addEventListener('mouseenter', () => {
                            if (glare) glare.style.opacity = '1';
                        });
                    })();
                    </script>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem Section — White Editorial Band -->
    <section class="py-section bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="text-center mb-16 flex flex-col items-center max-w-2xl mx-auto">
                <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-ink text-sm font-semibold tracking-wider uppercase mb-4">{{ data_get($problem, 'badge', 'MENGAPA KAMI HADIR?') }}</span>
                <h2 class="display-lg text-cb-ink mb-4">{{ data_get($problem, 'title', 'Membangun Identitas Seharusnya Tidak Membuat Anda Frustrasi') }}</h2>
                <p class="text-base text-cb-body leading-relaxed">{{ data_get($problem, 'description', 'Kami tahu betapa melelahkannya mencari vendor yang benar-benar peduli pada detail acara Anda. Pernahkah Anda merasakan kekhawatiran ini?') }}</p>
            </div>

            <div class="flex flex-wrap justify-center gap-6">
                @forelse($problemItems as $item)
                    <div class="flex-[1_1_350px] w-full max-w-[500px] bg-cb-canvas p-8 rounded-cb-xl border border-cb-hairline flex items-start gap-5 hover:shadow-[0_4px_12px_rgba(0,0,0,0.04)] transition-shadow">
                        <div class="w-11 h-11 rounded-full bg-cb-surface-strong flex items-center justify-center shrink-0">
                            <i data-lucide="{{ data_get($item, 'icon', 'sparkles') }}" class="w-5 h-5 text-cb-muted"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-cb-ink mb-1">{{ data_get($item, 'title', '') }}</h3>
                            <p class="text-sm text-cb-body leading-relaxed">{{ data_get($item, 'description', '') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-cb-muted">Belum ada item problem.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Process Section — Soft-Gray Elevation Band -->
    <section id="cerita" class="py-section bg-cb-surface-soft relative">
        <div class="max-w-[1000px] mx-auto px-6 relative">
            <div class="text-center mb-20 max-w-2xl mx-auto">
                <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-sm font-semibold tracking-wider uppercase mb-4">Cara Kami Bekerja</span>
                <h2 class="display-lg text-cb-ink mb-4">Perjalanan Menciptakan Kebanggaan</h2>
                <p class="text-base text-cb-body leading-relaxed">Bagi kami, ini bukan tentang seberapa banyak kami mencetak, tapi seberapa sempurna setiap helai tali itu mewakili nama besar Anda. Inilah janji kami.</p>
            </div>

            <div class="flex flex-col gap-12 pb-32">
                @php $firstStory = $storySteps[0] ?? []; @endphp
                <div class="sticky top-24 z-10 flex flex-col md:flex-row items-center gap-10 bg-cb-canvas p-8 border border-cb-hairline rounded-cb-xl transition-transform duration-500">
                    <div class="w-full md:w-1/2 aspect-video md:aspect-[4/3] relative rounded-cb-lg overflow-hidden bg-cb-surface-soft border border-cb-hairline-soft">
                        <img src="{{ data_get($firstStory, 'image_url', 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=800&q=80') }}" alt="{{ data_get($firstStory, 'title', 'Material Lembut') }}" class="object-cover w-full h-full">
                    </div>
                    <div class="w-full md:w-1/2 flex flex-col items-start text-left">
                        <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-ink text-sm font-semibold mb-6">{{ data_get($firstStory, 'number', '01') }}. Sentuhan Pertama</span>
                        <h3 class="display-sm text-cb-ink mb-4">{{ data_get($firstStory, 'title', 'Material Pilihan yang Menghargai Kenyamanan Tim Anda') }}</h3>
                        <p class="text-base text-cb-body leading-relaxed">
                            {{ data_get($firstStory, 'description', 'Kami percaya identitas yang baik harus terasa nyaman saat dikenakan. Lanyardbagus secara eksklusif memilih serat kain bertekstur halus yang ramah di kulit dan menyerap keringat. Tak ada lagi keluhan leher gatal saat meeting panjang atau melayani customer seharian.') }}
                        </p>
                    </div>
                </div>

                @php $secondStory = $storySteps[1] ?? []; @endphp
                <div class="sticky top-28 z-20 flex flex-col md:flex-row-reverse items-center gap-10 bg-cb-surface-soft p-8 border border-cb-hairline rounded-cb-xl shadow-[0_4px_12px_rgba(0,0,0,0.04)] transition-transform duration-500">
                    <div class="w-full md:w-1/2 aspect-video md:aspect-[4/3] relative rounded-cb-lg overflow-hidden bg-cb-surface-strong border border-cb-hairline-soft">
                        <img src="{{ data_get($secondStory, 'image_url', 'https://images.unsplash.com/photo-1614316315266-9e9ebdb1ce45?auto=format&fit=crop&w=800&q=80') }}" alt="{{ data_get($secondStory, 'title', 'Cetak Resolusi Tinggi') }}" class="object-cover w-full h-full">
                    </div>
                    <div class="w-full md:w-1/2 flex flex-col items-start text-left">
                        <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-ink text-sm font-semibold mb-6">{{ data_get($secondStory, 'number', '02') }}. Visual yang Berbicara</span>
                        <h3 class="display-sm text-cb-ink mb-4">{{ data_get($secondStory, 'title', 'Warna Tajam yang Meneriakkan Karakter Brand Anda') }}</h3>
                        <p class="text-base text-cb-body leading-relaxed">
                            {{ data_get($secondStory, 'description', 'Logo Anda menceritakan sejarah; warnanya membangkitkan emosi. Dengan teknologi cetak sublimasi resolusi tinggi kami, setiap detail kode warna (pantone) perusahaan Anda akan diterjemahkan dengan presisi mutlak. Warnanya terkunci kuat di serat kain, melawan pudar bertahun-tahun lamanya.') }}
                        </p>
                    </div>
                </div>

                <!-- Sticky Card 3 — Dark -->
                @php $thirdStory = $storySteps[2] ?? []; @endphp
                <div class="sticky top-32 z-30 flex flex-col md:flex-row items-center gap-10 bg-cb-surface-dark text-white p-8 border border-white/5 rounded-cb-xl shadow-lg transition-transform duration-500">
                    <div class="w-full md:w-1/2 aspect-video md:aspect-[4/3] relative rounded-cb-lg overflow-hidden bg-cb-surface-dark-el border border-white/5">
                        <img src="{{ data_get($thirdStory, 'image_url', 'https://images.unsplash.com/photo-1587293852726-70cdb56c2866?auto=format&fit=crop&w=800&q=80') }}" alt="{{ data_get($thirdStory, 'title', 'Pengiriman Tepat Waktu') }}" class="object-cover w-full h-full opacity-90">
                    </div>
                    <div class="w-full md:w-1/2 flex flex-col items-start text-left">
                        <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-dark-el text-cb-muted-soft text-sm font-semibold mb-6">{{ data_get($thirdStory, 'number', '03') }}. Eksekusi Tanpa Kompromi</span>
                        <h3 class="display-sm text-white mb-4">{{ data_get($thirdStory, 'title', 'Tiba Tepat Waktu, Jauh Sebelum Momen Besar Anda Dimulai') }}</h3>
                        <p class="text-base text-cb-muted-soft leading-relaxed">
                            {{ data_get($thirdStory, 'description', 'Kami mengambil alih kecemasan Anda. Sistem produksi efisien kami dirancang untuk satu janji: produk selesai dan diantarkan tepat waktu. Anda bisa fokus memikirkan kesuksesan acara Anda, biarkan urusan kelengkapan tim menjadi tanggung jawab dan dedikasi kami.') }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Pesan Online — Multi-Step Wizard -->
    <section id="hubungi" class="py-section bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[640px] mx-auto px-6">
            <div class="text-center mb-12">
                <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-sm font-semibold tracking-wider uppercase mb-4">{{ data_get($contact, 'badge', 'Pesan Online') }}</span>
                <h2 class="display-lg text-cb-ink mb-4">{{ data_get($contact, 'title', 'Mulai Pesanan Anda') }}</h2>
                <p class="text-base text-cb-body leading-relaxed max-w-md mx-auto">{{ data_get($contact, 'description', 'Sudah punya keputusan, siap memesan? Isi formulir di bawah dan tim kami akan segera menghubungi Anda.') }}</p>
            </div>

            <!-- Step Indicator -->
            <div class="flex items-center justify-center gap-2 mb-10">
                @foreach($contactSteps as $key => $stepLabel)
                    <div id="ind-{{ $loop->iteration }}" class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full {{ $loop->first ? 'bg-cb-blue text-white' : 'bg-cb-surface-strong text-cb-muted' }} text-sm font-semibold flex items-center justify-center">{{ $key }}</span>
                        <span class="text-sm font-medium {{ $loop->first ? 'text-cb-ink' : 'text-cb-muted' }} hidden sm:inline">{{ $stepLabel }}</span>
                    </div>
                    @unless($loop->last)
                        <div class="w-8 h-px bg-cb-hairline"></div>
                    @endunless
                @endforeach
            </div>

            <div class="bg-cb-canvas border border-cb-hairline rounded-cb-xl p-8 lg:p-10">
                <form id="lead-form" enctype="multipart/form-data">
                    <!-- Step 1: Data Diri -->
                    <div id="step-1" class="step-panel flex flex-col gap-5">
                        <div>
                            <label class="block text-sm font-medium text-cb-ink mb-2">{{ data_get($contactLabels, 'name', 'Nama Lengkap') }} <span class="text-cb-semantic-down">*</span></label>
                            <input type="text" name="name" placeholder="{{ data_get($contactPlaceholders, 'name', 'Masukkan nama lengkap Anda') }}" required class="w-full px-4 py-3 rounded-cb-md border border-cb-hairline bg-cb-canvas text-cb-ink placeholder-cb-muted-soft text-sm focus:outline-none focus:border-cb-blue focus:ring-1 focus:ring-cb-blue/30 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-cb-ink mb-2">{{ data_get($contactLabels, 'email', 'Email') }}</label>
                            <input type="email" name="email" placeholder="{{ data_get($contactPlaceholders, 'email', 'email@contoh.com') }}" class="w-full px-4 py-3 rounded-cb-md border border-cb-hairline bg-cb-canvas text-cb-ink placeholder-cb-muted-soft text-sm focus:outline-none focus:border-cb-blue focus:ring-1 focus:ring-cb-blue/30 transition-colors">
                        </div>
                        <button type="button" onclick="goStep(2)" class="inline-flex items-center justify-center gap-2 bg-cb-blue text-white px-8 py-3.5 rounded-cb-pill font-semibold text-base hover:bg-cb-blue-active transition-colors self-end"><span>{{ data_get($contactButtons, 'next', 'Lanjutkan') }}</span><i data-lucide="arrow-right" class="w-4 h-4"></i></button>
                    </div>

                    <!-- Step 2: Kontak -->
                    <div id="step-2" class="step-panel hidden flex flex-col gap-5">
                        <div>
                            <label class="block text-sm font-medium text-cb-ink mb-2">{{ data_get($contactLabels, 'whatsapp', 'No. WhatsApp') }} <span class="text-cb-semantic-down">*</span></label>
                            <input type="tel" name="whatsapp" placeholder="{{ data_get($contactPlaceholders, 'whatsapp', '08xx-xxxx-xxxx') }}" required class="w-full px-4 py-3 rounded-cb-md border border-cb-hairline bg-cb-canvas text-cb-ink placeholder-cb-muted-soft text-sm focus:outline-none focus:border-cb-blue focus:ring-1 focus:ring-cb-blue/30 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-cb-ink mb-2">{{ data_get($contactLabels, 'address', 'Alamat Lengkap') }}</label>
                            <textarea name="address" rows="3" placeholder="{{ data_get($contactPlaceholders, 'address', 'Alamat pengiriman Anda...') }}" class="w-full px-4 py-3 rounded-cb-md border border-cb-hairline bg-cb-canvas text-cb-ink placeholder-cb-muted-soft text-sm resize-none focus:outline-none focus:border-cb-blue focus:ring-1 focus:ring-cb-blue/30 transition-colors"></textarea>
                        </div>
                        <div class="flex justify-between">
                            <button type="button" onclick="goStep(1)" class="inline-flex items-center gap-2 text-cb-body font-semibold text-sm hover:text-cb-ink transition-colors"><i data-lucide="arrow-left" class="w-4 h-4"></i>{{ data_get($contactButtons, 'back', 'Kembali') }}</button>
                            <button type="button" onclick="goStep(3)" class="inline-flex items-center justify-center gap-2 bg-cb-blue text-white px-8 py-3.5 rounded-cb-pill font-semibold text-base hover:bg-cb-blue-active transition-colors"><span>{{ data_get($contactButtons, 'next', 'Lanjutkan') }}</span><i data-lucide="arrow-right" class="w-4 h-4"></i></button>
                        </div>
                    </div>

                    <!-- Step 3: Detail & Submit -->
                    <div id="step-3" class="step-panel hidden flex flex-col gap-5">
                        <div>
                            <label class="block text-sm font-medium text-cb-ink mb-2">{{ data_get($contactLabels, 'message', 'Pesan') }}</label>
                            <textarea name="message" rows="4" placeholder="{{ data_get($contactPlaceholders, 'message', 'Ceritakan kebutuhan lanyard Anda...') }}" class="w-full px-4 py-3 rounded-cb-md border border-cb-hairline bg-cb-canvas text-cb-ink placeholder-cb-muted-soft text-sm resize-none focus:outline-none focus:border-cb-blue focus:ring-1 focus:ring-cb-blue/30 transition-colors"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-cb-ink mb-2">{{ data_get($contactLabels, 'file', 'Upload Referensi Desain') }} <span class="text-cb-muted font-normal">(opsional, maks 5MB)</span></label>
                            <div id="drop-zone" class="relative border-2 border-dashed border-cb-hairline rounded-cb-lg p-6 text-center cursor-pointer hover:border-cb-blue/40 transition-colors bg-cb-surface-soft">
                                <input type="file" id="contact-file" name="file" accept="image/png,image/jpeg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div id="drop-zone-content" class="flex flex-col items-center gap-2">
                                    <i data-lucide="image-plus" class="w-8 h-8 text-cb-muted"></i>
                                    <p class="text-sm text-cb-body">{{ data_get($contactButtons, 'file', 'Klik atau seret gambar ke sini') }}</p>
                                    <p class="text-sm text-cb-muted">{{ data_get($contactHints, 'file', 'PNG, JPG, WebP — Maks. 5MB') }}</p>
                                </div>
                                <div id="drop-zone-preview" class="hidden items-center gap-4">
                                    <img id="preview-img" src="" alt="Preview" class="w-16 h-16 object-cover rounded-cb-sm border border-cb-hairline">
                                    <div class="text-left flex-1 min-w-0"><p id="preview-name" class="text-sm font-medium text-cb-ink truncate"></p><p id="preview-size" class="text-sm text-cb-muted"></p></div>
                                    <button type="button" id="remove-file" class="w-8 h-8 flex items-center justify-center rounded-full bg-cb-surface-strong text-cb-muted hover:text-cb-ink transition-colors shrink-0"><i data-lucide="x" class="w-4 h-4"></i></button>
                                </div>
                            </div>
                        </div>
                        <div id="form-status" class="hidden text-sm rounded-cb-md px-4 py-3"></div>
                        <div class="flex justify-between">
                            <button type="button" onclick="goStep(2)" class="inline-flex items-center gap-2 text-cb-body font-semibold text-sm hover:text-cb-ink transition-colors"><i data-lucide="arrow-left" class="w-4 h-4"></i>{{ data_get($contactButtons, 'back', 'Kembali') }}</button>
                            <button type="submit" id="submit-btn" class="inline-flex items-center justify-center gap-2 bg-cb-blue text-white px-8 py-3.5 rounded-cb-pill font-semibold text-base hover:bg-cb-blue-active transition-colors disabled:opacity-50 disabled:cursor-not-allowed"><i data-lucide="send" class="w-4 h-4"></i><span id="submit-text">{{ data_get($contactButtons, 'submit', 'Kirim Pesanan') }}</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Success Dialog -->
        <div id="success-dialog" class="fixed inset-0 z-[999] hidden items-center justify-center">
            <div id="success-overlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
            <div id="success-modal" class="relative bg-cb-canvas rounded-cb-xl border border-cb-hairline shadow-2xl p-10 max-w-sm w-full mx-4 text-center" style="animation: modalIn 0.3s ease">
                <div class="w-16 h-16 rounded-full bg-cb-semantic-up/10 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-cb-semantic-up" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path class="check-path" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-xl font-semibold text-cb-ink mb-2">Pesanan Terkirim!</h3>
                <p class="text-sm text-cb-body leading-relaxed mb-8">Terima kasih telah memesan. Tim kami akan menghubungi Anda melalui WhatsApp dalam waktu 1×24 jam.</p>
                <button onclick="closeSuccessDialog()" class="inline-flex items-center justify-center bg-cb-blue text-white px-8 py-3 rounded-cb-pill font-semibold text-sm hover:bg-cb-blue-active transition-colors w-full">Mengerti</button>
            </div>
        </div>
        <style>
            @keyframes modalIn { from { opacity:0; transform:scale(0.95) translateY(8px); } to { opacity:1; transform:scale(1) translateY(0); } }
            .check-path { stroke-dasharray: 24; stroke-dashoffset: 24; animation: drawCheck 0.5s 0.3s ease forwards; }
            @keyframes drawCheck { to { stroke-dashoffset: 0; } }
        </style>
    </section>

    <script>
    let currentStep = 1;
    const submitButtonLabel = @json(data_get($contactButtons, 'submit', 'Kirim Pesanan'));
    function goStep(n) {
        const form = document.getElementById('lead-form');
        if (n > currentStep) {
            const panel = document.getElementById('step-' + currentStep);
            const inputs = panel.querySelectorAll('[required]');
            for (const inp of inputs) { if (!inp.value.trim()) { inp.focus(); inp.classList.add('border-cb-semantic-down'); setTimeout(() => inp.classList.remove('border-cb-semantic-down'), 2000); return; } }
        }
        document.querySelectorAll('.step-panel').forEach(p => { p.classList.add('hidden'); p.classList.remove('flex'); });
        const target = document.getElementById('step-' + n);
        target.classList.remove('hidden'); target.classList.add('flex');
        currentStep = n;
        for (let i = 1; i <= 3; i++) {
            const ind = document.getElementById('ind-' + i);
            const circle = ind.querySelector('span');
            const label = ind.querySelectorAll('span')[1];
            if (i <= n) { circle.className = 'w-8 h-8 rounded-full bg-cb-blue text-white text-xs font-semibold flex items-center justify-center'; if(label) { label.className = 'text-sm font-medium text-cb-ink hidden sm:inline'; } }
            else { circle.className = 'w-8 h-8 rounded-full bg-cb-surface-strong text-cb-muted text-xs font-semibold flex items-center justify-center'; if(label) { label.className = 'text-sm font-medium text-cb-muted hidden sm:inline'; } }
        }
        lucide.createIcons();
    }
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('lead-form');
        const fileInput = document.getElementById('contact-file');
        const dropZone = document.getElementById('drop-zone');
        const dropContent = document.getElementById('drop-zone-content');
        const dropPreview = document.getElementById('drop-zone-preview');
        const previewImg = document.getElementById('preview-img');
        const previewName = document.getElementById('preview-name');
        const previewSize = document.getElementById('preview-size');
        const removeBtn = document.getElementById('remove-file');
        const statusEl = document.getElementById('form-status');
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        function showPreview(file) {
            if (file.size > 5*1024*1024) { showStatus('File melebihi 5MB.', true); fileInput.value=''; return; }
            const r = new FileReader(); r.onload = e => { previewImg.src = e.target.result; }; r.readAsDataURL(file);
            previewName.textContent = file.name; previewSize.textContent = (file.size/1024/1024).toFixed(2)+' MB';
            dropContent.classList.add('hidden'); dropPreview.classList.remove('hidden'); dropPreview.classList.add('flex');
        }
        fileInput.addEventListener('change', function(){ if(this.files[0]) showPreview(this.files[0]); });
        removeBtn.addEventListener('click', function(e){ e.stopPropagation(); fileInput.value=''; dropContent.classList.remove('hidden'); dropPreview.classList.add('hidden'); dropPreview.classList.remove('flex'); });
        ['dragover','dragenter'].forEach(ev => { dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.add('border-cb-blue/50'); }); });
        ['dragleave','drop'].forEach(ev => { dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.remove('border-cb-blue/50'); }); });
        dropZone.addEventListener('drop', e => { if(e.dataTransfer.files[0]){ fileInput.files=e.dataTransfer.files; showPreview(e.dataTransfer.files[0]); } });
        function showStatus(msg, isErr) { statusEl.textContent=msg; statusEl.className='text-sm rounded-cb-md px-4 py-3 '+(isErr?'bg-cb-semantic-down/10 text-cb-semantic-down':'bg-cb-semantic-up/10 text-cb-semantic-up'); statusEl.classList.remove('hidden'); }
        form.addEventListener('submit', async function(e) {
            e.preventDefault(); submitBtn.disabled=true; submitText.textContent='Mengirim...'; statusEl.classList.add('hidden');
            const fd = new FormData(form);
            try {
                const res = await fetch('/api/customizer/leads', { method:'POST', body:fd });
                const data = await res.json();
                if(res.ok){ form.reset(); dropContent.classList.remove('hidden'); dropPreview.classList.add('hidden'); dropPreview.classList.remove('flex'); goStep(1); showSuccessDialog(); }
                else { const err = data.errors ? Object.values(data.errors).flat().join(', ') : (data.message||'Terjadi kesalahan.'); showStatus(err, true); }
            } catch(err){ showStatus('Gagal mengirim. Periksa koneksi Anda.', true); }
            submitBtn.disabled=false; submitText.textContent=submitButtonLabel;
        });
    });
    function showSuccessDialog() { const d = document.getElementById('success-dialog'); d.classList.remove('hidden'); d.classList.add('flex'); }
    function closeSuccessDialog() { const d = document.getElementById('success-dialog'); d.classList.add('hidden'); d.classList.remove('flex'); }
    document.getElementById('success-overlay').addEventListener('click', closeSuccessDialog);
    </script>

    <!-- Customize Section — White Editorial Band -->
    <section id="produk" class="py-section bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="text-center mb-16 flex flex-col items-center">
                <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-sm font-semibold tracking-wider uppercase mb-4">{{ data_get($customize, 'badge', 'Koleksi Kami') }}</span>
                <h2 class="display-lg text-cb-ink">{{ data_get($customize, 'title', 'Pilih Kanvas untuk Identitas Anda') }}</h2>
            </div>

            <div class="flex flex-wrap justify-center gap-6 pb-8">
                @forelse($customizeItems as $item)
                    @php
                        $itemImage = data_get($item, 'image_url', '');
                        $itemIcon = data_get($item, 'icon', 'sparkles');
                        $itemHref = data_get($item, 'href', '#kontak');
                    @endphp
                    <a href="{{ $itemHref }}" class="flex-[1_1_300px] max-w-[380px] flex flex-col group cursor-pointer bg-cb-canvas border border-cb-hairline rounded-cb-xl p-6 transition-all hover:shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                        <div class="relative w-full aspect-[4/3] rounded-cb-lg overflow-hidden mb-5 bg-cb-surface-soft border border-cb-hairline-soft">
                            @if(filled($itemImage))
                                <img src="{{ $itemImage }}" alt="{{ data_get($item, 'title', 'Lanyard') }}" class="absolute inset-0 w-full h-full object-cover">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <i data-lucide="{{ $itemIcon }}" class="w-10 h-10 text-cb-hairline"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-semibold text-cb-ink">{{ data_get($item, 'title', '') }}</h3>
                            <i data-lucide="arrow-up-right" class="w-5 h-5 text-cb-muted group-hover:text-cb-blue transition-colors"></i>
                        </div>
                        <p class="text-sm text-cb-body text-left">{{ data_get($item, 'description', '') }}</p>
                    </a>
                @empty
                    <p class="text-cb-muted">Belum ada item customize.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Comparison Table — Soft-Gray Band -->
    <section id="perbandingan" class="py-section bg-cb-surface-soft border-b border-cb-hairline">
        <div class="max-w-[1000px] mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-semibold tracking-wider uppercase mb-4">Kenapa Kami?</span>
                <h2 class="display-lg text-cb-ink mb-4">Bandingkan Sendiri Perbedaannya</h2>
                <p class="text-base text-cb-body leading-relaxed max-w-xl mx-auto">Tidak semua vendor lanyard diciptakan sama. Lihat bagaimana Lanyardbagus unggul di setiap aspek yang penting.</p>
            </div>

            <div class="bg-cb-canvas border border-cb-hairline rounded-cb-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto overflow-y-hidden hide-scrollbar">
                    <table class="w-full min-w-[500px] text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="py-4 md:py-5 px-4 md:px-6 bg-cb-surface-soft border-b border-cb-hairline text-[10px] md:text-sm font-bold text-cb-muted uppercase tracking-wider">Fitur</th>
                                <th class="py-4 md:py-5 px-4 md:px-6 bg-cb-blue border-b border-cb-blue text-sm md:text-base font-bold text-white text-center">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                        Lanyardbagus
                                    </span>
                                </th>
                                <th class="py-4 md:py-5 px-4 md:px-6 bg-cb-surface-soft border-b border-cb-hairline text-sm md:text-base font-semibold text-cb-muted text-center">Vendor Lain</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b border-cb-hairline-soft">
                                <td class="py-4 md:py-5 px-4 md:px-6 text-cb-ink font-medium leading-tight">Cetak Sublimasi Resolusi Tinggi</td>
                                <td class="py-4 md:py-5 px-4 md:px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-cb-semantic-up/10 text-cb-semantic-up">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </span>
                                </td>
                                <td class="py-4 md:py-5 px-4 md:px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-cb-surface-strong text-cb-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    </span>
                                </td>
                            </tr>
                            <tr class="border-b border-cb-hairline-soft">
                                <td class="py-4 md:py-5 px-4 md:px-6 text-cb-ink font-medium leading-tight">Bahan Tissue Premium (Anti Gatal)</td>
                                <td class="py-4 md:py-5 px-4 md:px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-cb-semantic-up/10 text-cb-semantic-up">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </span>
                                </td>
                                <td class="py-4 md:py-5 px-4 md:px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-cb-surface-strong text-cb-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    </span>
                                </td>
                            </tr>
                            <tr class="border-b border-cb-hairline-soft">
                                <td class="py-4 md:py-5 px-4 md:px-6 text-cb-ink font-medium leading-tight">Garansi Warna Tidak Pudar</td>
                                <td class="py-4 md:py-5 px-4 md:px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-cb-semantic-up/10 text-cb-semantic-up">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </span>
                                </td>
                                <td class="py-4 md:py-5 px-4 md:px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-cb-surface-strong text-cb-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    </span>
                                </td>
                            </tr>
                            <tr class="border-b border-cb-hairline-soft">
                                <td class="py-4 md:py-5 px-4 md:px-6 text-cb-ink font-medium leading-tight">Desain Gratis oleh Tim Internal</td>
                                <td class="py-4 md:py-5 px-4 md:px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-cb-semantic-up/10 text-cb-semantic-up">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </span>
                                </td>
                                <td class="py-4 md:py-5 px-4 md:px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-cb-surface-strong text-cb-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    </span>
                                </td>
                            </tr>
                            <tr class="border-b border-cb-hairline-soft">
                                <td class="py-4 md:py-5 px-4 md:px-6 text-cb-ink font-medium leading-tight">Pengiriman Tepat Waktu (Dijamin)</td>
                                <td class="py-4 md:py-5 px-4 md:px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-cb-semantic-up/10 text-cb-semantic-up">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </span>
                                </td>
                                <td class="py-4 md:py-5 px-4 md:px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-cb-surface-strong text-cb-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-4 md:py-5 px-4 md:px-6 text-cb-ink font-medium leading-tight">Harga Bersaing (MOQ 50 pcs)</td>
                                <td class="py-4 md:py-5 px-4 md:px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-cb-semantic-up/10 text-cb-semantic-up">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </span>
                                </td>
                                <td class="py-4 md:py-5 px-4 md:px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-cb-semantic-up/10 text-cb-semantic-up">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-10 text-center">
                <a href="#kontak" class="inline-flex items-center justify-center gap-2 bg-cb-blue text-white px-8 py-3.5 rounded-cb-pill font-semibold text-base hover:bg-cb-blue-active transition-colors">
                    Mulai Pesan Sekarang <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Pricing — White Editorial Band -->
    <section id="harga" class="py-section bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-semibold tracking-wider uppercase mb-4">Transparan & Terjangkau</span>
                <h2 class="display-lg text-cb-ink">Estimasi Daftar Harga</h2>
            </div>
            <div class="bg-white border border-cb-hairline rounded-[32px] overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-cb-surface-soft border-b border-cb-hairline">
                                <th class="py-6 px-8 text-[10px] font-medium text-cb-muted uppercase tracking-[0.2em] min-w-[300px]">Produk</th>
                                <th class="py-6 px-8 text-[10px] font-medium text-cb-muted uppercase tracking-[0.2em] whitespace-nowrap">Kuantitas</th>
                                <th class="py-6 px-8 text-[10px] font-medium text-cb-muted uppercase tracking-[0.2em] whitespace-nowrap">Harga / pcs</th>
                                <th class="py-6 px-8 text-[10px] font-medium text-cb-muted uppercase tracking-[0.2em] whitespace-nowrap">Estimasi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <!-- Item 1 -->
                            <tr class="border-b border-cb-hairline-soft hover:bg-cb-surface-soft/50 transition-colors">
                                <td class="py-8 px-8 align-top">
                                    <div class="font-medium text-cb-ink mb-1">Printing Premium (2cm)</div>
                                    <div class="text-[11px] text-cb-muted leading-relaxed">Bahan Tissue, Stopper, Kait Besi</div>
                                </td>
                                <td class="py-8 px-8 align-top text-cb-body whitespace-nowrap">50 - 100 pcs</td>
                                <td class="py-8 px-8 align-top whitespace-nowrap">
                                    <span class="font-medium text-cb-ink">Rp 15.000</span>
                                </td>
                                <td class="py-8 px-8 align-top text-cb-muted text-xs whitespace-nowrap">3-5 Hari Kerja</td>
                            </tr>
                            <!-- Item 2 -->
                            <tr class="border-b border-cb-hairline-soft hover:bg-cb-surface-soft/50 transition-colors">
                                <td class="py-8 px-8 align-top">
                                    <div class="font-medium text-cb-ink mb-1">Printing Premium (2cm)</div>
                                    <div class="text-[11px] text-cb-muted leading-relaxed">Bahan Tissue, Stopper, Kait Besi</div>
                                </td>
                                <td class="py-8 px-8 align-top text-cb-body whitespace-nowrap">101 - 500 pcs</td>
                                <td class="py-8 px-8 align-top whitespace-nowrap">
                                    <span class="font-medium text-cb-blue">Rp 12.500</span>
                                </td>
                                <td class="py-8 px-8 align-top text-cb-muted text-xs whitespace-nowrap">4-7 Hari Kerja</td>
                            </tr>
                            <!-- Item 3 -->
                            <tr class="border-b border-cb-hairline-soft hover:bg-cb-surface-soft/50 transition-colors">
                                <td class="py-8 px-8 align-top">
                                    <div class="font-medium text-cb-ink mb-1">Paket Identitas Lengkap</div>
                                    <div class="text-[11px] text-cb-muted leading-relaxed">Lanyard + Holder PVC + ID Card</div>
                                </td>
                                <td class="py-8 px-8 align-top text-cb-body whitespace-nowrap">50 - 100 pcs</td>
                                <td class="py-8 px-8 align-top whitespace-nowrap">
                                    <span class="font-medium text-cb-ink">Rp 22.000</span>
                                </td>
                                <td class="py-8 px-8 align-top text-cb-muted text-xs whitespace-nowrap">3-5 Hari Kerja</td>
                            </tr>
                            <!-- Item 4 -->
                            <tr class="border-b border-cb-hairline-soft hover:bg-cb-surface-soft/50 transition-colors">
                                <td class="py-8 px-8 align-top">
                                    <div class="font-medium text-cb-ink mb-1">Lanyard 2 Sisi Bolak Balik</div>
                                    <div class="text-[11px] text-cb-muted leading-relaxed">Bahan Tissue 2.5cm Premium</div>
                                </td>
                                <td class="py-8 px-8 align-top text-cb-body whitespace-nowrap">Min 100 pcs</td>
                                <td class="py-8 px-8 align-top whitespace-nowrap">
                                    <span class="font-medium text-cb-ink">Rp 18.000</span>
                                </td>
                                <td class="py-8 px-8 align-top text-cb-muted text-xs whitespace-nowrap">5-7 Hari Kerja</td>
                            </tr>
                            <!-- Item 5 -->
                            <tr class="hover:bg-cb-surface-soft/50 transition-colors">
                                <td class="py-8 px-8 align-top">
                                    <div class="font-medium text-cb-ink mb-1 opacity-60">Partai Besar / Grosir</div>
                                    <div class="text-[11px] text-cb-muted leading-relaxed">Tersedia harga khusus event</div>
                                </td>
                                <td class="py-8 px-8 align-top text-cb-muted opacity-60 whitespace-nowrap">> 1000 pcs</td>
                                <td class="py-8 px-8 align-top whitespace-nowrap">
                                    <span class="font-medium text-cb-muted italic">Hubungi Kami</span>
                                </td>
                                <td class="py-8 px-8 align-top text-cb-muted text-xs italic whitespace-nowrap">Negosiasi</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-cb-canvas via-cb-canvas/80 to-transparent flex items-end justify-center pb-6">
                    <a href="#kontak" class="inline-flex items-center gap-2 bg-cb-blue text-white px-6 py-2.5 rounded-cb-pill font-semibold text-sm hover:bg-cb-blue-active transition-colors">
                        Unduh Price List Lengkap <i data-lucide="download" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ — White Editorial Band -->
    <section id="faq" class="py-section bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[800px] mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-semibold tracking-wider uppercase mb-4">{{ data_get($faqSection, 'badge', 'Tanya Jawab') }}</span>
                <h2 class="display-lg text-cb-ink">{{ data_get($faqSection, 'title', 'Pertanyaan Seputar Lanyard') }}</h2>
            </div>
            <div class="space-y-3">
                @forelse($faqItems as $faq)
                <details class="group bg-cb-canvas border border-cb-hairline rounded-cb-xl">
                    <summary class="flex items-center justify-between p-6 cursor-pointer font-semibold text-cb-ink">
                        <span>{{ data_get($faq, 'question', '') }}</span>
                        <span class="transition group-open:rotate-180"><i data-lucide="chevron-down" class="w-5 h-5 text-cb-muted"></i></span>
                    </summary>
                    <div class="px-6 pb-6 text-cb-body text-sm leading-relaxed">{{ data_get($faq, 'answer', '') }}</div>
                </details>
                @empty
                    <p class="text-cb-muted">Belum ada FAQ.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Blog — Soft-Gray Band -->
    <section id="blog" class="py-section bg-cb-surface-soft border-b border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-semibold tracking-wider uppercase mb-4">Artikel Terbaru</span>
                    <h2 class="display-lg text-cb-ink">Wawasan & Tips</h2>
                </div>
                <a href="#" class="hidden md:inline-flex items-center gap-2 text-cb-blue font-semibold hover:text-cb-blue-active transition">
                    Lihat Semua <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($posts as $post)
                <article class="bg-cb-canvas rounded-cb-xl border border-cb-hairline overflow-hidden group hover:shadow-[0_4px_12px_rgba(0,0,0,0.04)] transition-shadow">
                    <div class="aspect-video overflow-hidden bg-cb-surface-soft relative">
                        <img src="{{ $post->featuredImage->url ?? 'https://images.unsplash.com/photo-1558655146-d09347e92766?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 text-xs text-cb-muted mb-3">
                            <span class="font-semibold text-cb-blue">{{ $post->category->name ?? 'Berita' }}</span>
                            <span>•</span>
                            <span>{{ $post->published_at->translatedFormat('d M Y') }}</span>
                        </div>
                        <h3 class="font-semibold text-lg text-cb-ink mb-2 leading-tight">{{ $post->title }}</h3>
                        <p class="text-sm text-cb-body line-clamp-2 mb-4">{{ $post->excerpt }}</p>
                        <a href="/blog/{{ $post->slug }}" class="inline-flex items-center gap-1 text-sm font-semibold text-cb-blue hover:text-cb-blue-active transition">
                            Baca Artikel <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </article>
                @empty
                <p class="text-cb-muted col-span-3 text-center">Belum ada artikel terbaru.</p>
                @endforelse
            </div>

            <div class="mt-8 text-center md:hidden">
                <a href="#" class="inline-flex items-center justify-center bg-cb-surface-strong text-cb-ink px-6 py-3 rounded-cb-pill font-semibold hover:bg-cb-hairline transition-colors">
                    Lihat Semua Artikel
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Band — Dark -->
    <section id="kontak" class="py-section bg-cb-surface-dark">
        <div class="max-w-[1000px] mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="display-md text-white mb-6">{!! nl2br(e(data_get($ctaBand, 'title', "Setiap Tim Hebat Memiliki Cerita.\nMari Cetak Cerita Anda Bersama Kami."))) !!}</h2>
                <p class="text-cb-muted-soft text-base max-w-xl mx-auto mb-10 leading-relaxed">{{ data_get($ctaBand, 'description', 'Jangan biarkan desain terbaik Anda rusak oleh vendor yang salah. Hubungi kami hari ini untuk konsultasi bahan, desain gratis, dan amankan jadwal produksi Anda.') }}</p>
                <div class="flex flex-col sm:flex-row items-center gap-4 justify-center">
                    <a href="{{ data_get($ctaBand, 'primary_href', 'https://wa.me/' . preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '628000000000')) }}"
                        target="_blank" rel="nofollow noopener noreferrer"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-cb-blue text-white px-8 py-3.5 rounded-cb-pill font-semibold hover:bg-cb-blue-active transition-colors">
                        <i data-lucide="message-square" class="w-5 h-5"></i>
                        {{ data_get($ctaBand, 'primary_label', 'Pesan via WhatsApp') }}
                    </a>
                    <a href="{{ data_get($ctaBand, 'secondary_href', '#produk') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-cb-surface-dark-el text-white border border-white/10 px-8 py-3.5 rounded-cb-pill font-semibold hover:border-white/20 transition-colors">
                        {{ data_get($ctaBand, 'secondary_label', 'Lihat Daftar Harga') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        // Mobile Menu Logic
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const hamburgerIcon = document.getElementById('menu-icon-hamburger');
        const closeIcon = document.getElementById('menu-icon-close');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
        const mobileMenuLinks = document.querySelectorAll('.mobile-menu-link');

        function toggleMenu() {
            if (!mobileMenuOverlay) return;
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
            mobileMenuBackdrop?.classList.remove('hidden');
            hamburgerIcon?.classList.add('hidden');
            closeIcon?.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeMenu() {
            mobileMenuOverlay.classList.add('hidden');
            mobileMenuOverlay.classList.remove('flex');
            mobileMenuBackdrop?.classList.add('hidden');
            hamburgerIcon?.classList.remove('hidden');
            closeIcon?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleMenu);
        if (mobileMenuBackdrop) mobileMenuBackdrop.addEventListener('click', closeMenu);
        
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', closeMenu);
        });
    </script>

    <!-- Lucide Icons -->
    <script src="/js/lucide.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>
</body>
</html>
