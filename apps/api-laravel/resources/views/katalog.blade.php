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
            <h1 class="text-4xl md:text-5xl font-medium text-cb-ink mb-6 tracking-tight">Semua Produk Custom Kami</h1>
            <p class="text-base text-cb-body leading-relaxed max-w-2xl mx-auto opacity-80">
                Pilih produk yang Anda butuhkan dan pesan langsung online. Semua bisa dicustom sesuai kebutuhan brand Anda.
            </p>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="py-16 bg-cb-canvas">
        <div class="max-w-[1200px] mx-auto px-6">
            @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                <div class="bg-white border border-cb-hairline rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-lg hover:border-cb-blue/20">
                    <!-- Image -->
                    <div class="aspect-square bg-cb-surface-soft overflow-hidden">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-cb-muted">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>
                    <!-- Info -->
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex px-2.5 py-1 bg-cb-surface-soft text-cb-blue text-xs rounded-lg font-medium">{{ $product->category->name }}</span>
                        </div>
                        <h3 class="text-lg font-medium text-cb-ink mb-2">{{ $product->name }}</h3>
                        @if($product->description)
                            <p class="text-sm text-cb-body opacity-70 mb-4 line-clamp-2">{{ $product->description }}</p>
                        @endif

                        <!-- Specs -->
                        @if($product->specs && count($product->specs) > 0)
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($product->specs as $key => $value)
                            <span class="inline-flex px-2.5 py-1 bg-cb-surface-soft text-cb-body text-xs rounded-lg">
                                {{ $key }}: {{ $value }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        <!-- Price & Order -->
                        <div class="flex items-end justify-between pt-4 border-t border-cb-hairline-soft">
                            <div>
                                <span class="text-xs text-cb-muted">Mulai dari</span>
                                <p class="text-xl font-semibold text-cb-ink">Rp {{ number_format($product->price, 0, ',', '.') }}<span class="text-sm font-normal text-cb-muted">/pcs</span></p>
                                <span class="text-xs text-cb-muted">Min. {{ $product->min_order }} pcs</span>
                            </div>
                            <a href="/order?product_id={{ $product->id }}" class="inline-flex px-5 py-2.5 bg-cb-ink text-white text-sm font-medium rounded-cb-pill hover:bg-cb-blue transition-colors">
                                Pesan
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-20">
                <p class="text-cb-muted text-lg mb-2">Belum ada produk</p>
                <a href="/" class="inline-flex mt-4 px-6 py-3 bg-cb-ink text-white text-sm font-medium rounded-cb-pill hover:bg-cb-blue transition-colors">Kembali ke Beranda</a>
            </div>
            @endif
        </div>
    </section>
@endsection
