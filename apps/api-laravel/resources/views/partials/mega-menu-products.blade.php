{{--
    Mega Menu — product cards
    Requires: $megaFeaturedProducts (Collection, max 4 items)
--}}
<div style="display:flex; gap:8px;">
    @foreach($megaFeaturedProducts as $fp)
        @php
            $fpImg = (is_array($fp->images) && count($fp->images) > 0)
                ? $fp->images[0]
                : ($fp->category?->image ?? null);
        @endphp
        <a href="/order?product_id={{ $fp->id }}"
           style="width:100px; flex-shrink:0; display:flex; flex-direction:column; border-radius:10px; overflow:hidden; border:1px solid #dee1e6; text-decoration:none; transition:border-color .15s, box-shadow .15s;"
           onmouseenter="this.style.borderColor='rgba(0,82,255,.4)';this.style.boxShadow='0 2px 8px rgba(0,82,255,.1)'"
           onmouseleave="this.style.borderColor='#dee1e6';this.style.boxShadow='none'">
            {{-- Image --}}
            <div style="width:100px; height:80px; overflow:hidden; background:#f7f7f7; flex-shrink:0;">
                @if($fpImg)
                    <img src="{{ $fpImg }}" alt="{{ $fp->name }}"
                         style="width:100%; height:100%; object-fit:cover; display:block;"
                         loading="lazy">
                @else
                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                        <svg width="24" height="24" fill="none" stroke="#a8acb3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v13.5A1.5 1.5 0 003.75 21z"/>
                        </svg>
                    </div>
                @endif
            </div>
            {{-- Info --}}
            <div style="padding:6px 8px 8px; background:#fff;">
                <p style="font-size:11px; font-weight:500; color:#0a0b0d; line-height:1.3; margin:0; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">{{ $fp->name }}</p>
                @if($fp->price > 0)
                <p style="font-size:10px; color:#7c828a; margin:2px 0 0;">Rp {{ number_format($fp->price, 0, ',', '.') }}/pcs</p>
                @endif
            </div>
        </a>
    @endforeach
</div>
<div style="margin-top:10px; padding-top:10px; border-top:1px solid #dee1e6;">
    <a href="/katalog" style="display:inline-flex; align-items:center; gap:4px; font-size:12px; font-weight:500; color:#0052ff; text-decoration:none;"
       onmouseenter="this.style.color='#003ecc'" onmouseleave="this.style.color='#0052ff'">
        Lihat semua produk
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
        </svg>
    </a>
</div>
