@if($posts->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($posts as $post)
            <article class="group relative bg-white border border-gray-100 flex flex-col h-full transition-all duration-300 hover:shadow-[0_4px_24px_rgba(0,0,0,0.08)]" style="border-radius:10px; overflow:hidden;">
                {{-- Thumbnail --}}
                <a href="/blog/{{ $post->slug }}" class="block relative aspect-[16/10] overflow-hidden bg-gray-50" style="border-radius:10px 10px 0 0;">
                    @if($post->featuredImage)
                        <img src="{{ $post->featuredImage->url }}" alt="{{ $post->title }}"
                            class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-50">
                            <i data-lucide="image" class="w-10 h-10 text-gray-300"></i>
                        </div>
                    @endif
                </a>

                {{-- Body --}}
                <div class="flex flex-col flex-grow p-5">

                    {{-- Meta --}}
                    <div class="flex items-center gap-2 mb-3">
                        @if($post->category)
                            <a href="/blog/category/{{ $post->category->slug }}"
                                class="text-sm font-semibold text-cb-blue hover:underline transition-colors relative z-10">
                                {{ $post->category->name }}
                            </a>
                            <span class="text-gray-300 text-sm">•</span>
                        @endif
                        <span class="text-sm text-gray-400">{{ $post->published_at->format('d M Y') }}</span>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-base font-bold text-gray-900 leading-snug mb-2 group-hover:text-cb-blue transition-colors">
                        <a href="/blog/{{ $post->slug }}" class="after:absolute after:inset-0">{{ $post->title }}</a>
                    </h3>

                    {{-- Excerpt --}}
                    @if($post->excerpt)
                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 mb-4 flex-grow">
                        {{ $post->excerpt }}
                    </p>
                    @else
                    <div class="flex-grow"></div>
                    @endif

                    {{-- Footer --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100 relative z-10 mt-auto">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="shrink-0">
                                @if(optional($post->author)->avatar_url)
                                    <img src="{{ $post->author->avatar_url }}" alt="{{ $post->author->name }}" class="w-7 h-7 rounded-full object-cover border border-gray-200">
                                @else
                                    <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 text-xs font-bold border border-gray-200">
                                        {{ strtoupper(substr(optional($post->author)->name ?? optional($post->author)->username ?? 'A', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <span class="text-sm font-medium text-gray-600 truncate">
                                {{ optional($post->author)->name ?? optional($post->author)->username ?? 'Admin' }}
                            </span>
                        </div>
                        <a href="/blog/{{ $post->slug }}"
                            class="text-sm font-semibold text-cb-blue hover:text-cb-blue-active inline-flex items-center gap-1 transition-colors shrink-0 relative z-10">
                            Baca <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@else
    <div class="text-center py-24 bg-white border border-gray-100" style="border-radius:10px;">
        <i data-lucide="file-x-2" class="w-14 h-14 text-gray-300 mx-auto mb-5"></i>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Belum ada artikel</h3>
        <p class="text-gray-500 text-sm max-w-sm mx-auto mb-7">Saat ini belum ada artikel yang dipublikasikan untuk bagian ini.</p>
        <a href="/blog" class="inline-flex items-center gap-2 px-5 py-2.5 bg-cb-ink text-white font-semibold text-sm hover:bg-cb-blue transition-colors" style="border-radius:999px;">
            Lihat Semua Artikel
        </a>
    </div>
@endif
