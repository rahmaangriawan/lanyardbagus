{{-- Mega Menu: product card grid + "lihat semua" footer --}}
{{-- Requires $megaFeaturedProducts to be set in the parent view --}}
<div class="grid grid-cols-4 gap-2" style="grid-template-columns: repeat(4, 100px);">
    @foreach($megaFeaturedProducts as $fp)
        @php
            $fpImg = (is_array($fp->images) && count($fp->images) > 0)
                ? $fp->images[0]
                : ($fp->category?->image ?? null);
            $fpPrice = $fp->price > 0
                ? 'Rp ' . number_format($fp->price, 0, ',', '.') . '/pcs'
                : 'Lihat harga';
        @endphp
        <a href="/order?product_id={{ $fp->id }}"
           class="group/fp flex flex-col rounded-lg overflow-hidden border border-cb-hairline hover:border-cb-blue/50 hover:shadow-sm transition-all duration-200 w-[100px]">
            {{-- Image: fixed 100×80 --}}
            <div class="w-full h-[80px] bg-cb-surface-soft overflow-hidden shrink-0">
                @if($fpImg)
                    <img
                        src="{{ $fpImg }}"
                        alt="{{ $fp->name }}"
                        class="w-full h-full object-cover group-hover/fp:scale-105 transition-transform duration-300"
                        loading="lazy"
                    >
                @else
                    <div class="w-full h-full flex items-center justify-center text-cb-muted-soft">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                                d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v13.5A1.5 1.5 0 003.75 21z"/>
                        </svg>
                    </div>
                @endif
            </div>
            {{-- Info --}}
            <div class="px-2 py-1.5 bg-white">
                <p class="text-[11px] font-medium text-cb-ink leading-tight line-clamp-2 group-hover/fp:text-cb-blue transition-colors">{{ $fp->name }}</p>
                <p class="text-[10px] text-cb-muted mt-0.5">{{ $fpPrice }}</p>
            </div>
        </a>
    @endforeach
</div>
<div class="mt-2.5 pt-2.5 border-t border-cb-hairline">
    <a href="/katalog" class="flex items-center gap-1 text-xs font-medium text-cb-blue hover:text-cb-blue-active transition-colors">
        Lihat semua produk
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
        </svg>
    </a>
</div>
