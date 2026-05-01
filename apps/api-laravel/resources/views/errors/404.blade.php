@extends('layouts.main')

@section('title', 'Halaman Tidak Ditemukan - ' . ($settings->site_title ?? 'Lanyardbagus'))

@section('content')
<div class="min-h-screen pt-16 flex items-center justify-center bg-cb-canvas px-6">
    <div class="max-w-md w-full text-center">
        <!-- 404 Illustration / Icon -->
        <div class="mb-10 relative">
            <div class="text-[120px] font-black text-cb-surface-strong leading-none select-none">404</div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-20 h-20 bg-cb-blue/10 rounded-full flex items-center justify-center text-cb-blue animate-pulse">
                    <i data-lucide="ghost" class="w-10 h-10"></i>
                </div>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-cb-ink mb-4 tracking-tight">Oops! Halaman Hilang</h1>
        <p class="text-cb-body mb-10 leading-relaxed">
            Sepertinya halaman yang Anda cari telah berpindah alamat atau tidak pernah ada. Mari kembali ke jalan yang benar.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/" class="w-full sm:w-auto px-8 py-3.5 bg-cb-blue text-white font-bold rounded-cb-lg hover:bg-cb-blue-active transition-all shadow-lg shadow-cb-blue/20">
                Kembali ke Beranda
            </a>
            <a href="/blog" class="w-full sm:w-auto px-8 py-3.5 bg-white border border-cb-hairline text-cb-ink font-bold rounded-cb-lg hover:border-cb-blue/30 transition-all">
                Baca Blog
            </a>
        </div>

        <!-- Search Suggestion -->
        <div class="mt-16 pt-8 border-t border-gray-50">
            <p class="text-xs font-bold text-cb-muted uppercase tracking-widest mb-6">Atau cari sesuatu yang lain</p>
            <form action="/blog" method="GET" class="relative group">
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Cari artikel atau panduan..." 
                    class="w-full pl-12 pr-4 py-3 bg-cb-surface-soft border border-transparent rounded-cb-md text-sm focus:outline-none focus:ring-2 focus:ring-cb-blue/20 focus:bg-white focus:border-cb-blue transition-all"
                >
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-cb-muted group-focus-within:text-cb-blue transition-colors">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
