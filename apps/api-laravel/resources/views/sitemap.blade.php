@extends('layouts.main')

@section('title', 'Peta Situs - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', 'Peta situs lengkap Lanyardbagus. Temukan semua halaman, artikel, dan kategori dalam satu tempat.')

@push('head')
<meta name="robots" content="noindex, follow">
@endpush

@section('content')
    @php
        $totalUrls = count($statics) + count($posts) + count($categories) + 1;
    @endphp

    <!-- Hero Section -->
    <section class="pt-40 pb-20 bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[800px] mx-auto px-6 text-center">
            <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-semibold tracking-wider uppercase mb-6">
                Peta Situs
            </span>
            <h1 class="display-lg text-cb-ink mb-6">Jelajahi Semua Halaman</h1>
            <p class="text-lg text-cb-body leading-relaxed mb-8">
                Temukan seluruh konten website kami dalam satu tempat. 
                <span class="font-semibold text-cb-ink">{{ $totalUrls }} URL</span> tersedia untuk Anda jelajahi.
            </p>
            <div class="flex items-center justify-center gap-4 text-sm text-cb-muted">
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    {{ count($statics) + 1 }} Halaman
                </span>
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-cb-blue"></span>
                    {{ count($posts) }} Artikel
                </span>
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                    {{ count($categories) }} Kategori
                </span>
            </div>
        </div>
    </section>

    <!-- Sitemap Content -->
    <section class="py-section bg-cb-canvas">
        <div class="max-w-[1000px] mx-auto px-6">
            
            <!-- Static Pages -->
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                        <i data-lucide="file-text" class="w-5 h-5 text-green-600"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-cb-ink">Halaman Utama</h2>
                        <p class="text-sm text-cb-muted">{{ count($statics) + 1 }} halaman statis</p>
                    </div>
                </div>
                <div class="bg-cb-canvas rounded-cb-xl border border-cb-hairline overflow-hidden">
                    <ul class="divide-y divide-cb-hairline">
                        <li>
                            <a href="/" class="flex items-center justify-between p-4 hover:bg-cb-surface-soft transition-colors group">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="home" class="w-4 h-4 text-cb-muted group-hover:text-cb-blue transition-colors"></i>
                                    <span class="font-medium text-cb-ink">Beranda</span>
                                </div>
                                <span class="text-xs text-cb-muted">Priority: 1.0</span>
                            </a>
                        </li>
                        @foreach($statics as $url)
                            @php
                                $labels = [
                                    'jenis' => 'Jenis Material',
                                    'produk' => 'Produk',
                                    'harga' => 'Harga',
                                    'faq' => 'FAQ',
                                    'kontak' => 'Kontak',
                                    'tentang' => 'Tentang Kami',
                                    'pengiriman' => 'Pengiriman',
                                    'kebijakan' => 'Kebijakan',
                                    'blog' => 'Blog'
                                ];
                                $label = $labels[$url] ?? ucfirst($url);
                                $icons = [
                                    'jenis' => 'layers',
                                    'produk' => 'package',
                                    'harga' => 'tag',
                                    'faq' => 'help-circle',
                                    'kontak' => 'mail',
                                    'tentang' => 'building-2',
                                    'pengiriman' => 'truck',
                                    'kebijakan' => 'shield',
                                    'blog' => 'newspaper'
                                ];
                                $icon = $icons[$url] ?? 'link';
                            @endphp
                            <li>
                                <a href="/{{ $url }}" class="flex items-center justify-between p-4 hover:bg-cb-surface-soft transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="{{ $icon }}" class="w-4 h-4 text-cb-muted group-hover:text-cb-blue transition-colors"></i>
                                        <span class="font-medium text-cb-ink">{{ $label }}</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="text-xs text-cb-muted hidden sm:block">/{{ $url }}</span>
                                        <i data-lucide="external-link" class="w-4 h-4 text-cb-muted opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Blog Posts -->
            @if(count($posts) > 0)
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-cb-blue/10 flex items-center justify-center">
                        <i data-lucide="newspaper" class="w-5 h-5 text-cb-blue"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-cb-ink">Artikel Blog</h2>
                        <p class="text-sm text-cb-muted">{{ count($posts) }} artikel terbaru</p>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach($posts as $post)
                    <a href="/blog/{{ $post->slug }}" class="group bg-cb-canvas rounded-cb-xl border border-cb-hairline p-4 hover:border-cb-blue/30 hover:shadow-lg transition-all">
                        <div class="flex items-start gap-3">
                            <div class="w-16 h-16 rounded-lg bg-cb-surface-soft flex items-center justify-center shrink-0 overflow-hidden">
                                @if($post->featuredImage)
                                    <img src="{{ $post->featuredImage->url }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <i data-lucide="file-text" class="w-6 h-6 text-cb-muted"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-cb-ink line-clamp-2 group-hover:text-cb-blue transition-colors">{{ $post->title }}</h3>
                                <p class="text-xs text-cb-muted mt-1">{{ $post->updated_at->format('d M Y') }}</p>
                            </div>
                            <i data-lucide="arrow-up-right" class="w-4 h-4 text-cb-muted opacity-0 group-hover:opacity-100 transition-opacity shrink-0"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Categories -->
            @if(count($categories) > 0)
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center">
                        <i data-lucide="folder" class="w-5 h-5 text-purple-600"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-cb-ink">Kategori</h2>
                        <p class="text-sm text-cb-muted">{{ count($categories) }} kategori artikel</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    @foreach($categories as $category)
                    <a href="/blog/category/{{ $category->slug }}" class="inline-flex items-center gap-2 px-4 py-2 bg-cb-canvas border border-cb-hairline rounded-cb-pill hover:border-cb-blue/30 hover:bg-cb-surface-soft transition-all group">
                        <i data-lucide="hash" class="w-4 h-4 text-cb-muted group-hover:text-cb-blue transition-colors"></i>
                        <span class="font-medium text-cb-ink">{{ $category->name }}</span>
                        <i data-lucide="arrow-up-right" class="w-3 h-3 text-cb-muted opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- XML Download -->
            <div class="bg-cb-surface-soft rounded-cb-xl p-6 border border-cb-hairline">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center">
                            <i data-lucide="code-xml" class="w-6 h-6 text-orange-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-cb-ink">Sitemap XML</h3>
                            <p class="text-sm text-cb-muted">Untuk keperluan SEO dan search engines</p>
                        </div>
                    </div>
                    <a href="/sitemap.xml" class="inline-flex items-center gap-2 bg-cb-canvas text-cb-ink border border-cb-hairline px-6 py-3 rounded-cb-pill font-medium hover:bg-cb-surface-strong transition-all">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        Download XML
                    </a>
                </div>
            </div>

        </div>
    </section>
@endsection
