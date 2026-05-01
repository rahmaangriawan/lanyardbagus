@if($posts->lastPage() > 1)
<div class="flex items-center justify-center gap-2 flex-wrap">
    {{-- Previous --}}
    @if($posts->currentPage() > 1)
        <button data-page="{{ $posts->currentPage() - 1 }}"
            class="w-10 h-10 rounded-full border border-cb-hairline flex items-center justify-center text-cb-ink hover:border-cb-blue hover:text-cb-blue transition-colors cursor-pointer">
            <i data-lucide="chevron-left" class="w-4 h-4"></i>
        </button>
    @endif

    {{-- Page Numbers --}}
    @php
        $start = max(1, $posts->currentPage() - 2);
        $end   = min($posts->lastPage(), $posts->currentPage() + 2);
    @endphp

    @if($start > 1)
        <button data-page="1" class="w-10 h-10 rounded-full border border-cb-hairline text-sm font-semibold text-cb-ink hover:border-cb-blue hover:text-cb-blue transition-colors cursor-pointer">1</button>
        @if($start > 2)<span class="text-cb-muted px-1">…</span>@endif
    @endif

    @for($i = $start; $i <= $end; $i++)
        @if($i === $posts->currentPage())
            <button class="w-10 h-10 rounded-full bg-cb-ink text-white text-sm font-bold shadow-sm cursor-default" disabled>{{ $i }}</button>
        @else
            <button data-page="{{ $i }}" class="w-10 h-10 rounded-full border border-cb-hairline text-sm font-semibold text-cb-ink hover:border-cb-blue hover:text-cb-blue transition-colors cursor-pointer">{{ $i }}</button>
        @endif
    @endfor

    @if($end < $posts->lastPage())
        @if($end < $posts->lastPage() - 1)<span class="text-cb-muted px-1">…</span>@endif
        <button data-page="{{ $posts->lastPage() }}" class="w-10 h-10 rounded-full border border-cb-hairline text-sm font-semibold text-cb-ink hover:border-cb-blue hover:text-cb-blue transition-colors cursor-pointer">{{ $posts->lastPage() }}</button>
    @endif

    {{-- Next --}}
    @if($posts->currentPage() < $posts->lastPage())
        <button data-page="{{ $posts->currentPage() + 1 }}"
            class="w-10 h-10 rounded-full border border-cb-hairline flex items-center justify-center text-cb-ink hover:border-cb-blue hover:text-cb-blue transition-colors cursor-pointer">
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </button>
    @endif
</div>
@endif
