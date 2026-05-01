@extends('layouts.main')

@section('title', 'Daftar Penulis - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', 'Kenali para penulis dan kontributor ahli di Lanyardbagus yang membagikan wawasan seputar branding dan identitas perusahaan.')

@section('content')
@push('head')
<!-- Authors List Schema -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "CollectionPage",
  "name": "Daftar Penulis - {{ $settings->site_title ?? 'Lanyardbagus' }}",
  "description": "Kenali para penulis dan kontributor ahli di Lanyardbagus yang membagikan wawasan seputar branding dan identitas perusahaan.",
  "mainEntity": {
    "@@type": "ItemList",
    "itemListElement": [
      @foreach($authors as $author)
      {
        "@@type": "ListItem",
        "position": {{ $loop->iteration }},
        "item": {
          "@@type": "Person",
          "name": "{{ $author->name ?? $author->username }}",
          "url": "{{ url('/author/' . \Illuminate\Support\Str::slug($author->username ?? '')) }}",
          "image": "{{ $author->avatar_url ?? '' }}",
          "description": "{{ $author->bio ?? '' }}"
        }
      }{{ !$loop->last ? ',' : '' }}
      @endforeach
    ]
  }
}
</script>
@endpush

<div class="pt-28 pb-24 bg-cb-canvas min-h-screen">
    <div class="max-w-[1200px] mx-auto px-6">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-cb-muted mb-8 gap-2">
            <a href="/" class="hover:text-cb-blue transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-cb-ink font-medium">Penulis</span>
        </nav>

        <!-- Header -->
        <div class="mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-cb-ink mb-4 tracking-tight">Tim Penulis Kami</h1>
            <p class="text-cb-body text-lg max-w-2xl leading-relaxed">
                Kumpulan pemikiran kreatif dan ahli industri yang berkomitmen memberikan panduan terbaik untuk identitas profesional Anda.
            </p>
        </div>

        <!-- Authors Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($authors as $author)
                <div class="group bg-white border border-gray-100 p-8 flex flex-col items-center text-center transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:border-cb-blue/20" style="border-radius:20px;">
                    
                    {{-- Avatar --}}
                    <div class="relative mb-6">
                        <div class="absolute -inset-1 bg-gradient-to-tr from-cb-blue to-purple-400 rounded-full blur opacity-0 group-hover:opacity-20 transition duration-500"></div>
                        @if($author->avatar_url)
                            <img src="{{ $author->avatar_url }}" alt="{{ $author->name }}" class="relative w-24 h-24 rounded-full object-cover border-4 border-white shadow-sm shrink-0">
                        @else
                            <div class="relative w-24 h-24 rounded-full bg-cb-surface-strong flex items-center justify-center text-cb-muted font-bold text-3xl border-4 border-white shadow-sm shrink-0">
                                {{ strtoupper(substr($author->name ?? $author->username ?? 'A', 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <h3 class="text-xl font-bold text-cb-ink mb-1 group-hover:text-cb-blue transition-colors">
                        {{ $author->name ?? $author->username }}
                    </h3>
                    
                    @if($author->role)
                        <span class="inline-block px-3 py-0.5 bg-cb-blue/5 text-cb-blue text-[10px] font-bold rounded-full uppercase tracking-widest mb-4">
                            {{ $author->role->name }}
                        </span>
                    @endif

                    <p class="text-cb-body text-sm leading-relaxed line-clamp-3 mb-8 px-2 italic">
                        {{ $author->bio ? '"' . $author->bio . '"' : '' }}
                    </p>

                    {{-- Action --}}
                    <div class="mt-auto pt-6 border-t border-gray-50 w-full flex items-center justify-between">
                        <div class="text-left">
                            <div class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Artikel</div>
                            <div class="text-sm font-bold text-cb-ink">{{ $author->posts_count }} Post</div>
                        </div>
                        <a href="/author/{{ \Illuminate\Support\Str::slug($author->username ?? '') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-50 text-cb-ink group-hover:bg-cb-blue group-hover:text-white transition-all duration-300">
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
