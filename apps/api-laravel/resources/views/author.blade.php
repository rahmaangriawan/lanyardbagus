@extends('layouts.main')

@section('title', 'Penulis: ' . ($author->name ?? $author->username) . ' - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', 'Artikel-artikel yang ditulis oleh ' . ($author->name ?? $author->username) . ' di Lanyardbagus.')

@push('head')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Person",
  "name": "{{ $author->name ?? $author->username }}",
  "url": "{{ url()->current() }}",
  "image": "{{ $author->avatar_url ?? '' }}",
  "description": "{{ str_replace(['"', "\n", "\r"], '', $author->bio ?? 'Penulis di Lanyardbagus') }}",
  "worksFor": {
    "@@type": "Organization",
    "name": "{{ $settings->site_title ?? 'Lanyardbagus' }}",
    "url": "{{ url('/') }}"
  }
}
</script>
@endpush

@section('content')
<div class="pt-28 pb-24 bg-cb-canvas min-h-screen">
    <div class="max-w-[1200px] mx-auto px-6">

        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-cb-muted mb-12 gap-2">
            <a href="/" class="hover:text-cb-blue transition-colors">Beranda</a>
            <span>/</span>
            <a href="/blog" class="hover:text-cb-blue transition-colors">Blog</a>
            <span>/</span>
            <a href="/author" class="hover:text-cb-blue transition-colors">Penulis</a>
            <span>/</span>
            <span class="text-cb-ink font-medium">{{ $author->name ?? $author->username }}</span>
        </nav>

        <!-- Author Box -->
        <div class="bg-cb-surface-soft rounded-cb-2xl border border-cb-hairline p-10 md:p-12 mb-16 flex flex-col sm:flex-row items-center sm:items-start gap-8 text-center sm:text-left">
            <div class="shrink-0">
                @if($author->avatar_url)
                    <img src="{{ $author->avatar_url }}" alt="{{ $author->name }}" class="w-24 h-24 rounded-full object-cover border-2 border-cb-hairline shadow">
                @else
                    <div class="w-24 h-24 rounded-full bg-cb-surface-strong border-2 border-cb-hairline shadow flex items-center justify-center text-cb-muted font-bold text-3xl">
                        {{ strtoupper(substr($author->name ?? $author->username ?? 'A', 0, 1)) }}
                    </div>
                @endif
            </div>

            <div class="flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-cb-ink tracking-tight">
                        {{ $author->name ?? $author->username }}
                    </h1>
                </div>

                <p class="text-cb-body leading-relaxed max-w-2xl">
                    {{ $author->bio }}
                </p>
                <div class="mt-6 flex items-center justify-center sm:justify-start gap-2">
                    <span class="text-sm text-cb-muted font-medium">
                        <strong class="text-cb-ink font-bold">{{ $posts->total() }}</strong> artikel diterbitkan
                    </span>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mb-10">
            <h2 class="text-2xl font-bold text-cb-ink">Artikel oleh {{ $author->name ?? $author->username }}</h2>
        </div>

        <div id="posts-container">
            @include('partials.posts-grid', ['posts' => $posts])
        </div>

        <div id="pagination-container" class="flex justify-center mt-12">
            @include('partials.pagination-numbers', ['posts' => $posts])
        </div>

    </div>
</div>

<script>
(function () {
    const baseUrl = window.location.href.split('?')[0];

    function loadPage(page) {
        const url = `${baseUrl}?page=${page}&ajax=1`;
        const container = document.getElementById('posts-container');
        if (!container) return;
        
        container.style.opacity = '0.5';
        container.style.transition = 'opacity 0.2s ease';

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                document.getElementById('posts-container').innerHTML = data.posts_html;
                document.getElementById('pagination-container').innerHTML = data.pagination_html;
                container.style.opacity = '1';
                window.scrollTo({ top: 0, behavior: 'smooth' });
                if (typeof lucide !== 'undefined') lucide.createIcons();
                bindPagination();
            })
            .catch(() => { container.style.opacity = '1'; });
    }

    function bindPagination() {
        document.querySelectorAll('[data-page]').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                loadPage(this.dataset.page);
            });
        });
    }

    bindPagination();
})();
</script>
@endsection
