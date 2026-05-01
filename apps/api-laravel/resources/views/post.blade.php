@extends('layouts.main')

@section('title', ($post->seo_title ?? $post->title) . ' - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', $post->meta_description ?? $post->excerpt ?? '')

@push('head')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BlogPosting",
  "headline": "{{ $post->title }}",
  "description": "{{ str_replace(['"', "\n", "\r"], '', $post->excerpt ?? $post->title) }}",
  "image": "{{ $post->featuredImage ? $post->featuredImage->url : '' }}",
  "author": {
    "@@type": "Person",
    "name": "{{ $post->author->name ?? $post->author->username ?? 'Admin' }}",
    "url": "{{ url('/author/' . \Illuminate\Support\Str::slug($post->author->username ?? '')) }}"
  },
  "publisher": {
    "@@type": "Organization",
    "name": "{{ $settings->site_title ?? 'Lanyardbagus' }}",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ $settings->logoImage ? $settings->logoImage->url : '' }}"
    }
  },
  "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}",
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ url()->current() }}"
  }
}
</script>
@endpush

@section('content')
    <div class="pt-28 pb-24 bg-cb-canvas">
        <article class="max-w-[1200px] mx-auto px-6">

            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm text-cb-muted mb-6 gap-2">
                <a href="/" class="hover:text-cb-blue transition-colors">Beranda</a>
                <span>/</span>
                <a href="/blog" class="hover:text-cb-blue transition-colors">Blog</a>
                @if($post->category)
                    <span>/</span>
                    <a href="/blog/category/{{ $post->category->slug }}" class="hover:text-cb-blue transition-colors"><span class="text-cb-ink font-medium">{{ $post->category->name }}</span></a>
                @endif
            </nav>

            <!-- Header Artikel -->
            <header class="mb-10">
                @if($post->category)
                    <div class="mb-5">
                        <a href="/blog/category/{{ $post->category->slug }}" class="inline-block hover:opacity-80 transition-opacity">
                            <span class="text-cb-blue text-sm font-semibold tracking-wide uppercase">
                                {{ $post->category->name }}
                            </span>
                        </a>
                    </div>
                @endif

                <h1
                    class="text-4xl md:text-5xl lg:text-[56px] font-bold text-cb-ink tracking-tight mb-6 leading-[1.15] max-w-4xl">
                    {{ $post->title }}
                </h1>

                @if($post->excerpt)
                    <p class="text-xl text-cb-body max-w-3xl leading-relaxed mb-10">
                        {{ $post->excerpt }}
                    </p>
                @endif

                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 py-6 border-y border-cb-hairline">
                    <!-- Author Info -->
                    <div class="flex items-center gap-4">
                        @if(optional($post->author)->avatar_url)
                            <img src="{{ $post->author->avatar_url }}" alt="{{ $post->author->name }}" class="w-12 h-12 rounded-full object-cover shadow-sm border border-cb-hairline shrink-0">
                        @else
                            <div class="w-12 h-12 rounded-full bg-cb-surface-strong flex items-center justify-center text-cb-muted font-bold text-lg shrink-0">
                                {{ strtoupper(substr(optional($post->author)->name ?? optional($post->author)->username ?? 'A', 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="font-semibold text-cb-ink">{{ optional($post->author)->name ?? optional($post->author)->username ?? 'Admin Lanyardbagus' }}</div>
                            <div class="text-sm text-cb-muted">
                                {{ $post->published_at ? $post->published_at->format('d M Y') : 'Draft' }}
                                @if($post->read_time)
                                    • {{ $post->read_time }} min read
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Share Buttons -->
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-cb-muted mr-2 font-medium">Bagikan:</span>
                        <button
                            onclick="navigator.clipboard.writeText(window.location.href); const btn=this; const old=btn.innerHTML; btn.innerHTML='<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'></path></svg>'; setTimeout(()=>btn.innerHTML=old, 2000);"
                            class="w-10 h-10 rounded-full border border-cb-hairline flex items-center justify-center text-cb-ink hover:border-cb-blue hover:text-cb-blue transition-colors"
                            title="Salin Tautan">
                            <i data-lucide="link-2" class="w-4 h-4"></i>
                        </button>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($post->title) }}"
                            target="_blank" rel="nofollow noopener noreferrer"
                            class="w-10 h-10 rounded-full border border-cb-hairline flex items-center justify-center text-cb-ink hover:border-[#1DA1F2] hover:text-[#1DA1F2] transition-colors"
                            title="Bagikan ke X/Twitter">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="https://threads.net/intent/post?text={{ urlencode($post->title . ' ' . request()->fullUrl()) }}"
                            target="_blank" rel="nofollow noopener noreferrer"
                            class="w-10 h-10 rounded-full border border-cb-hairline flex items-center justify-center text-cb-ink hover:border-black hover:text-black transition-colors"
                            title="Bagikan ke Threads">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <g fill="none" fill-rule="evenodd">
                                    <path
                                        d="m12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036q-.016-.004-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z" />
                                    <path fill="currentColor"
                                        d="M5.45 4.623C6.997 2.974 9.24 2 12.017 2c4.565 0 7.592 2.631 8.55 6.277a1 1 0 0 1-1.935.508C17.905 6.022 15.676 4 12.016 4c-2.286 0-3.98.789-5.106 1.99c-1.136 1.212-1.769 2.923-1.769 4.885v2.25c0 1.962.633 3.673 1.769 4.884C8.036 19.212 9.73 20 12.016 20c1.655 0 2.968-.384 3.976-1.005c1.015-.625 1.62-1.454 1.79-2.405c.195-1.096-.045-1.848-.458-2.391a3.2 3.2 0 0 0-.814-.741c-.135.749-.376 1.456-.74 2.01c-1.342 2.039-3.908 2.214-5.6 1.539c-.916-.365-1.701-1.364-1.945-2.435a3 3 0 0 1 .141-1.843c.265-.636.756-1.178 1.455-1.59c.692-.409 1.72-.597 2.739-.625c.614-.017 1.28.024 1.95.133c-.14-.65-.377-1.135-.644-1.384c-.484-.45-1.286-.767-2.09-.762c-.777.006-1.436.305-1.83.935a1 1 0 1 1-1.696-1.06c.837-1.338 2.216-1.865 3.513-1.875c1.27-.009 2.578.472 3.466 1.298c.898.836 1.284 2.207 1.384 3.454c.874.381 1.7.94 2.305 1.737c.781 1.03 1.117 2.358.833 3.951c-.29 1.624-1.315 2.898-2.71 3.757C15.673 21.54 13.985 22 12.016 22c-2.776 0-5.02-.974-6.565-2.623c-1.536-1.638-2.31-3.864-2.31-6.252v-2.25c0-2.388.774-4.614 2.31-6.252ZM14.6 12.7a8.4 8.4 0 0 0-1.986-.186c-.891.024-1.516.193-1.777.347c-.384.227-.55.458-.624.637a1.02 1.02 0 0 0-.038.63c.122.536.525.938.736 1.021c1.126.45 2.535.212 3.188-.78c.235-.358.422-.96.5-1.669Z" />
                                </g>
                            </svg> </a>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            @if($post->featuredImage)
                <div class="mb-16 rounded-cb-xl overflow-hidden bg-cb-surface-soft">
                    <img src="{{ $post->featuredImage->url }}" alt="{{ $post->title }}"
                        class="w-full aspect-[21/9] object-cover">
                </div>
            @endif

            <!-- Main Content & Sidebar Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">

                <!-- Left Sidebar -->
                <aside class="lg:col-span-4 hidden lg:block space-y-10">
                    <!-- Promo Box (Replacing Newsletter) -->
                    <div class="bg-cb-surface-soft p-8 rounded-cb-xl border border-cb-hairline">
                        <h3 class="text-xl font-bold text-cb-ink mb-3">Pesan Lanyard Kustom Anda</h3>
                        <p class="text-sm text-cb-body mb-6 leading-relaxed">
                            Tingkatkan identitas profesional tim Anda dengan lanyard premium berkualitas tinggi dari
                            Lanyardbagus.
                        </p>
                        <a href="/#hubungi"
                            class="flex items-center justify-center w-full py-3 bg-cb-ink text-white rounded-cb-md font-semibold hover:bg-cb-blue transition-colors">
                            Konsultasi Gratis
                        </a>
                    </div>

                    <!-- Recent / More Articles -->
                    @php
                        $recentPosts = \App\Models\Post::where('id', '!=', $post->id)
                            ->where('status', 'PUBLISHED')
                            ->latest('published_at')
                            ->take(4)
                            ->get();
                    @endphp
                    @if($recentPosts->count() > 0)
                        <div>
                            <h3 class="text-sm font-bold text-cb-ink uppercase tracking-wider mb-6">Artikel Terbaru</h3>
                            <div class="space-y-6">
                                @foreach($recentPosts as $recent)
                                    <a href="/blog/{{ $recent->slug }}" class="group block">
                                        <h4
                                            class="text-base font-semibold text-cb-ink leading-snug group-hover:text-cb-blue transition-colors mb-2">
                                            {{ $recent->title }}
                                        </h4>
                                        <span class="text-sm text-cb-muted">{{ $recent->published_at->format('d M Y') }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>

                <!-- Main Prose Content -->
                <div class="lg:col-span-8">
                    <div
                        class="prose prose-lg prose-slate max-w-none prose-headings:font-bold prose-headings:text-cb-ink prose-p:text-cb-body prose-p:leading-relaxed prose-a:text-cb-blue hover:prose-a:text-cb-blue-active prose-img:rounded-cb-lg prose-blockquote:border-l-4 prose-blockquote:border-cb-blue prose-blockquote:bg-cb-surface-soft prose-blockquote:py-2 prose-blockquote:px-6 prose-blockquote:rounded-r-lg prose-blockquote:font-medium prose-blockquote:text-cb-ink prose-blockquote:not-italic">
                        {!! $post->content !!}
                    </div>

                    <!-- Tags & Bottom Share -->
                    <div
                        class="mt-16 pt-8 border-t border-cb-hairline flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div class="flex flex-wrap gap-2">
                            @foreach($post->tags as $tag)
                                <a href="/blog/tag/{{ $tag->slug }}" class="px-4 py-1.5 bg-cb-surface-strong text-cb-ink text-sm font-medium rounded-cb-pill hover:bg-cb-blue hover:text-white transition-colors">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-cb-ink mr-2">Share:</span>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($post->title) }}"
                                target="_blank" rel="nofollow noopener noreferrer"
                                class="text-cb-muted hover:text-[#1DA1F2] transition-colors"
                                title="Bagikan ke X/Twitter"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                </svg></a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                target="_blank" rel="nofollow noopener noreferrer"
                                class="text-cb-muted hover:text-[#1877F2] transition-colors"
                                title="Bagikan ke Facebook"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg></a>
                            <a href="https://threads.net/intent/post?text={{ urlencode($post->title . ' ' . request()->fullUrl()) }}"
                                target="_blank" rel="nofollow noopener noreferrer"
                                class="text-cb-muted hover:text-black transition-colors" title="Bagikan ke Threads"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <g fill="none" fill-rule="evenodd">
                                        <path
                                            d="m12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036q-.016-.004-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z" />
                                        <path fill="currentColor"
                                            d="M5.45 4.623C6.997 2.974 9.24 2 12.017 2c4.565 0 7.592 2.631 8.55 6.277a1 1 0 0 1-1.935.508C17.905 6.022 15.676 4 12.016 4c-2.286 0-3.98.789-5.106 1.99c-1.136 1.212-1.769 2.923-1.769 4.885v2.25c0 1.962.633 3.673 1.769 4.884C8.036 19.212 9.73 20 12.016 20c1.655 0 2.968-.384 3.976-1.005c1.015-.625 1.62-1.454 1.79-2.405c.195-1.096-.045-1.848-.458-2.391a3.2 3.2 0 0 0-.814-.741c-.135.749-.376 1.456-.74 2.01c-1.342 2.039-3.908 2.214-5.6 1.539c-.916-.365-1.701-1.364-1.945-2.435a3 3 0 0 1 .141-1.843c.265-.636.756-1.178 1.455-1.59c.692-.409 1.72-.597 2.739-.625c.614-.017 1.28.024 1.95.133c-.14-.65-.377-1.135-.644-1.384c-.484-.45-1.286-.767-2.09-.762c-.777.006-1.436.305-1.83.935a1 1 0 1 1-1.696-1.06c.837-1.338 2.216-1.865 3.513-1.875c1.27-.009 2.578.472 3.466 1.298c.898.836 1.284 2.207 1.384 3.454c.874.381 1.7.94 2.305 1.737c.781 1.03 1.117 2.358.833 3.951c-.29 1.624-1.315 2.898-2.71 3.757C15.673 21.54 13.985 22 12.016 22c-2.776 0-5.02-.974-6.565-2.623c-1.536-1.638-2.31-3.864-2.31-6.252v-2.25c0-2.388.774-4.614 2.31-6.252ZM14.6 12.7a8.4 8.4 0 0 0-1.986-.186c-.891.024-1.516.193-1.777.347c-.384.227-.55.458-.624.637a1.02 1.02 0 0 0-.038.63c.122.536.525.938.736 1.021c1.126.45 2.535.212 3.188-.78c.235-.358.422-.96.5-1.669Z" />
                                    </g>
                                </svg></a>
                        </div>
                    </div>

                    <!-- Author Profile -->
                    @php $postAuthor = optional($post->author); @endphp
                    <div class="mt-12 p-8 bg-cb-surface-soft rounded-cb-xl border border-cb-hairline flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                        {{-- Avatar --}}
                        @if($postAuthor->avatar_url)
                            <a href="/author/{{ \Illuminate\Support\Str::slug($postAuthor->username ?? '') }}" class="shrink-0 block hover:opacity-90 transition-opacity">
                                <img src="{{ $postAuthor->avatar_url }}" alt="{{ $postAuthor->name }}"
                                    class="w-20 h-20 rounded-full object-cover border-2 border-cb-hairline shadow-sm">
                            </a>
                        @else
                            <a href="/author/{{ \Illuminate\Support\Str::slug($postAuthor->username ?? '') }}" class="shrink-0 block hover:opacity-90 transition-opacity">
                                <div class="w-20 h-20 rounded-full bg-cb-surface-strong flex items-center justify-center text-cb-muted font-bold text-2xl border-2 border-cb-hairline shadow-sm">
                                    {{ strtoupper(substr($postAuthor->name ?? 'A', 0, 1)) }}
                                </div>
                            </a>
                        @endif

                        {{-- Info --}}
                        <div>
                            <p class="text-sm text-cb-muted font-medium mb-1">Ditulis oleh</p>
                            <h4 class="text-xl font-bold text-cb-ink mb-1">
                                <a href="/author/{{ \Illuminate\Support\Str::slug($postAuthor->username ?? '') }}" class="hover:text-cb-blue transition-colors">{{ $postAuthor->name ?? $postAuthor->username ?? 'Admin Lanyardbagus' }}</a>
                            </h4>
                            <p class="text-cb-body text-sm leading-relaxed">
                                {{ $postAuthor->bio ?? 'Tim redaksi Lanyardbagus yang berkomitmen membagikan wawasan terbaik seputar desain identitas perusahaan, material lanyard premium, dan tips branding profesional.' }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </article>
    </div>

    <!-- Related Articles Section -->
    @php
        $relatedPosts = \App\Models\Post::where('id', '!=', $post->id)
            ->where('status', 'PUBLISHED')
            ->when($post->category_id, function ($q) use ($post) {
                $q->where('category_id', $post->category_id);
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        // Fallback if not enough related in same category
        if ($relatedPosts->count() < 3) {
            $fallback = \App\Models\Post::where('id', '!=', $post->id)
                ->whereNotIn('id', $relatedPosts->pluck('id')->toArray())
                ->where('status', 'PUBLISHED')
                ->latest('published_at')
                ->take(3 - $relatedPosts->count())
                ->get();
            $relatedPosts = $relatedPosts->merge($fallback);
        }
    @endphp

    @if($relatedPosts->count() > 0)
        <section class="py-20 bg-cb-surface-soft border-t border-cb-hairline">
            <div class="max-w-[1200px] mx-auto px-6">
                <div class="flex justify-between items-end mb-12">
                    <h2 class="text-3xl font-bold text-cb-ink tracking-tight">Artikel Terkait</h2>
                    <a href="/blog"
                        class="hidden sm:inline-flex items-center gap-2 text-cb-ink font-semibold hover:text-cb-blue transition-colors">
                        Lihat Semua <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedPosts as $related)
                        <article class="group">
                            <a href="/blog/{{ $related->slug }}" class="block">
                                <div class="aspect-[16/10] overflow-hidden rounded-cb-lg bg-cb-surface-strong mb-5">
                                    <img src="{{ $related->featuredImage->url ?? 'https://images.unsplash.com/photo-1558655146-d09347e92766?auto=format&fit=crop&w=600&q=80' }}"
                                        alt="{{ $related->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                                <div class="flex items-center gap-2 text-sm text-cb-muted mb-3 font-medium uppercase tracking-wide">
                                    <span class="text-cb-blue">{{ $related->category->name ?? 'Info' }}</span>
                                    <span>•</span>
                                    <span>{{ $related->read_time ?? '3' }} MIN READ</span>
                                </div>
                                <h3
                                    class="text-xl font-bold text-cb-ink leading-snug group-hover:text-cb-blue transition-colors mb-3">
                                    {{ $related->title }}
                                </h3>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Call To Action -->
    <section class="py-24 bg-cb-canvas border-t border-cb-hairline relative overflow-hidden">
        <!-- Abstract Shapes -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-cb-blue/5 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-cb-accent-yellow/5 blur-3xl"></div>

        <div class="max-w-[1000px] mx-auto px-6 relative z-10">
            <div
                class="bg-cb-surface-dark rounded-[32px] p-12 md:p-16 text-center text-white shadow-2xl overflow-hidden relative">
                <div class="absolute inset-0 opacity-20"
                    style="background-image: radial-gradient(circle at center, rgba(255,255,255,0.2) 0%, transparent 60%);">
                </div>

                <div class="relative z-10">
                    <span
                        class="inline-block px-4 py-1.5 rounded-cb-pill bg-white/10 text-white text-sm font-semibold tracking-widest uppercase mb-6 backdrop-blur-sm border border-white/10">
                        Mulai Sekarang
                    </span>
                    <h2 class="text-4xl md:text-5xl font-bold mb-6 tracking-tight leading-tight">
                        Siap Mengangkat<br>Identitas Tim Anda?
                    </h2>
                    <p class="text-cb-muted-soft text-lg max-w-2xl mx-auto mb-10">
                        Konsultasikan desain, bahan, dan kebutuhan lanyard Anda bersama tim kami. Kami siap mewujudkannya
                        dengan kualitas terbaik.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="/#hubungi"
                            class="w-full sm:w-auto px-8 py-4 bg-cb-blue hover:bg-cb-blue-active text-white rounded-cb-pill font-bold text-base transition-colors shadow-[0_0_20px_rgba(0,82,255,0.4)]">
                            Konsultasi Desain Gratis
                        </a>
                        <a href="/#produk"
                            class="w-full sm:w-auto px-8 py-4 bg-transparent border border-white/20 hover:bg-white/5 text-white rounded-cb-pill font-bold text-base transition-colors">
                            Lihat Daftar Harga
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection