@extends('layouts.main')

@section('title', $archiveTitle . ' - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', $archiveDescription)

@push('head')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "CollectionPage",
  "name": "{{ $archiveTitle }} - {{ $settings->site_title ?? 'Lanyardbagus' }}",
  "description": "{{ $archiveDescription }}",
  "url": "{{ url()->current() }}",
  "mainEntity": {
    "@@type": "ItemList",
    "itemListElement": [
      @foreach($posts as $post)
      {
        "@@type": "ListItem",
        "position": {{ $loop->iteration }},
        "url": "{{ url('/blog/' . $post->slug) }}",
        "name": "{{ $post->title }}"
      }{{ !$loop->last ? ',' : '' }}
      @endforeach
    ]
  }
}
</script>
@endpush

@section('content')
<div class="pt-28 pb-24 bg-cb-canvas min-h-screen">
    <div class="max-w-[1200px] mx-auto px-6">

        <!-- Header -->
        <header class="mb-16 text-center max-w-3xl mx-auto">
            <nav class="flex items-center justify-center text-sm text-cb-muted mb-6 gap-2">
                <a href="/" class="hover:text-cb-blue transition-colors">Beranda</a>
                <span>/</span>
                <a href="/blog" class="hover:text-cb-blue transition-colors">Blog</a>
                @if(isset($category))
                    <span>/</span>
                    <span class="text-cb-ink font-medium">{{ $category->name }}</span>
                @elseif(isset($tag))
                    <span>/</span>
                    <span class="text-cb-ink font-medium">{{ $tag->name }}</span>
                @endif
            </nav>
            <h1 class="text-4xl md:text-5xl font-bold text-cb-ink tracking-tight mb-6">
                {{ $archiveTitle }}
            </h1>
            <p class="text-lg text-cb-body leading-relaxed mb-10">
                {{ $archiveDescription }}
            </p>

            <!-- Search Bar -->
            <div class="relative max-w-xl mx-auto">
                <form action="/blog" method="GET" class="relative group">
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ $search ?? '' }}"
                        placeholder="Cari artikel..." 
                        class="w-full pl-12 pr-4 py-4 bg-white border border-cb-hairline rounded-cb-lg text-cb-ink focus:outline-none focus:ring-2 focus:ring-cb-blue/20 focus:border-cb-blue transition-all shadow-sm group-hover:border-cb-blue/30"
                    >
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-cb-muted group-hover:text-cb-blue transition-colors">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </div>
                    @if(isset($search) && filled($search))
                        <a href="/blog" class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-cb-muted hover:text-cb-blue uppercase tracking-wider">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </header>

        <!-- Posts Grid (AJAX Target) -->
        <div id="posts-container">
            @include('partials.posts-grid', ['posts' => $posts])
        </div>

        <!-- Pagination Numbering (AJAX Target) -->
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
        
        // Loading state
        const container = document.getElementById('posts-container');
        container.style.opacity = '0.5';
        container.style.transition = 'opacity 0.2s ease';

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                document.getElementById('posts-container').innerHTML = data.posts_html;
                document.getElementById('pagination-container').innerHTML = data.pagination_html;

                container.style.opacity = '1';
                window.scrollTo({ top: 0, behavior: 'smooth' });

                // Re-init lucide icons for newly rendered content
                if (typeof lucide !== 'undefined') lucide.createIcons();

                // Re-bind pagination clicks
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

    // Initial bind
    bindPagination();
})();
</script>
@endsection
