@extends('layouts.main')

@section('title', 'Katalog Produk - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', 'Katalog produk lengkap: lanyard, wristband, keychain, ID card, dan card holder custom.')

@section('robots')
<meta name="robots" content="noindex, nofollow">
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="pt-40 pb-20 bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[800px] mx-auto px-6 text-center">
            <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-sm font-semibold tracking-wider uppercase mb-6">Katalog</span>
            <h1 class="text-4xl md:text-5xl font-medium text-cb-ink mb-6 tracking-tight">Produk Custom Untuk Identitas Brand Anda</h1>
            <p class="text-base text-cb-body leading-relaxed max-w-2xl mx-auto opacity-80">
                Pilih kategori produk dan pesan langsung online. Semua produk bisa dicustom sesuai kebutuhan Anda.
            </p>
        </div>
    </section>

    <!-- Category Grid -->
    <section class="py-20 bg-cb-canvas">
        <div class="max-w-[1200px] mx-auto px-6">
            @if($categories->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                <a href="/katalog/{{ $category->slug }}" class="group block">
                    <div class="bg-white border border-cb-hairline rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-lg hover:border-cb-blue/20 hover:-translate-y-1">
                        <!-- Image -->
                        <div class="aspect-[4/3] bg-cb-surface-soft overflow-hidden">
                            @if($category->image)
                                <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-cb-muted">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                            @endif
                        </div>
                        <!-- Info -->
                        <div class="p-6">
                            <h2 class="text-xl font-medium text-cb-ink mb-2 group-hover:text-cb-blue transition-colors">{{ $category->name }}</h2>
                            @if($category->description)
                                <p class="text-sm text-cb-body opacity-70 mb-3 line-clamp-2">{{ $category->description }}</p>
                            @endif
                            <span class="text-xs font-medium text-cb-blue uppercase tracking-widest">{{ $category->active_products_count }} produk →</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-20">
                <p class="text-cb-muted text-lg">Belum ada kategori produk</p>
                <a href="/" class="inline-flex mt-4 px-6 py-3 bg-cb-ink text-white text-sm font-medium rounded-cb-pill hover:bg-cb-blue transition-colors">Kembali ke Beranda</a>
            </div>
            @endif
        </div>
    </section>
@endsection
