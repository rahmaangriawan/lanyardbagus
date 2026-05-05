@extends('layouts.main')

@section('title', 'Promo & Penawaran Spesial - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', 'Dapatkan penawaran terbaik lanyard custom, ID card, wristband, dan aksesoris branding. Diskon kuantitas, gratis desain, dan promo terbatas setiap bulan.')

@section('content')

    {{-- ── Hero ── --}}
    <section class="pt-36 pb-16 bg-gradient-to-b from-cb-surface-soft to-cb-canvas border-b border-cb-hairline relative overflow-hidden">
        {{-- decorative blobs --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-cb-blue/5 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-72 h-72 rounded-full bg-cb-accent-yellow/8 blur-3xl pointer-events-none"></div>

        <div class="max-w-[800px] mx-auto px-6 text-center relative">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-cb-pill bg-cb-blue/10 text-cb-blue text-sm font-semibold tracking-wider uppercase mb-6">
                <span style="display:inline-block;animation:fire-shake 1.8s ease-in-out infinite;transform-origin:bottom center">🔥</span>
                Promo Terbatas
            </div>
            <h1 class="display-md text-cb-ink mb-5 tracking-tight">Penawaran Spesial<br>Bulan Ini</h1>
            <p class="text-base text-cb-body leading-relaxed max-w-xl mx-auto">
                Hemat lebih banyak untuk pesanan lanyard, ID card, dan aksesoris branding berkualitas premium. Promo berlaku selama stok dan kuota masih tersedia.
            </p>
        </div>
    </section>

    {{-- ── Promo Cards ── --}}
    <section class="py-20 bg-cb-canvas">
        <div class="max-w-[1200px] mx-auto px-6">

            {{-- Section title --}}
            <div class="mb-12">
                <h2 class="title-lg text-cb-ink mb-2">Promo Aktif</h2>
                <p class="text-cb-body text-sm">Klik promo untuk langsung pesan atau konsultasi dengan tim kami.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Promo 1: Diskon Kuantitas --}}
                <div class="group relative flex flex-col rounded-[24px] overflow-hidden border border-cb-hairline hover:border-cb-blue/30 hover:shadow-xl transition-all duration-300 bg-white">
                    <div class="h-3 bg-gradient-to-r from-cb-blue to-blue-400"></div>
                    <div class="p-7 flex flex-col flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <span class="text-3xl">📦</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-cb-pill bg-cb-blue/10 text-cb-blue text-xs font-semibold">Hemat s/d 30%</span>
                        </div>
                        <h3 class="text-lg font-semibold text-cb-ink mb-2">Diskon Kuantitas</h3>
                        <p class="text-sm text-cb-body leading-relaxed flex-1">Semakin banyak pesan, semakin hemat. Berlaku untuk semua jenis lanyard dan ID card.</p>
                        <ul class="mt-4 space-y-1.5 text-sm text-cb-body">
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-cb-blue shrink-0"></span>100–499 pcs → diskon 10%</li>
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-cb-blue shrink-0"></span>500–999 pcs → diskon 20%</li>
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-cb-blue shrink-0"></span>1.000+ pcs → diskon 30%</li>
                        </ul>
                        <a href="/#hubungi" class="mt-6 inline-flex items-center justify-center gap-2 bg-cb-blue text-white text-sm font-semibold px-5 py-3 rounded-cb-pill hover:bg-cb-blue-active transition-colors">
                            Pesan Sekarang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Promo 2: Gratis Desain --}}
                <div class="group relative flex flex-col rounded-[24px] overflow-hidden border border-cb-hairline hover:border-cb-accent-yellow/50 hover:shadow-xl transition-all duration-300 bg-white">
                    <div class="h-3 bg-gradient-to-r from-cb-accent-yellow to-orange-400"></div>
                    <div class="p-7 flex flex-col flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <span class="text-3xl">🎨</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-cb-pill bg-cb-accent-yellow/15 text-amber-700 text-xs font-semibold">Gratis</span>
                        </div>
                        <h3 class="text-lg font-semibold text-cb-ink mb-2">Gratis Jasa Desain</h3>
                        <p class="text-sm text-cb-body leading-relaxed flex-1">Tidak punya file desain? Tim desainer kami siap membantu mewujudkan ide branding Anda tanpa biaya tambahan.</p>
                        <ul class="mt-4 space-y-1.5 text-sm text-cb-body">
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>Berlaku untuk min. order 100 pcs</li>
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>Revisi desain hingga 3x</li>
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>Proses desain 1–2 hari kerja</li>
                        </ul>
                        <a href="/#hubungi" class="mt-6 inline-flex items-center justify-center gap-2 bg-cb-accent-yellow text-white text-sm font-semibold px-5 py-3 rounded-cb-pill hover:opacity-90 transition-opacity">
                            Konsultasi Desain
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Promo 3: Paket Event --}}
                <div class="group relative flex flex-col rounded-[24px] overflow-hidden border border-cb-hairline hover:border-emerald-400/50 hover:shadow-xl transition-all duration-300 bg-white">
                    <div class="h-3 bg-gradient-to-r from-cb-semantic-up to-emerald-400"></div>
                    <div class="p-7 flex flex-col flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <span class="text-3xl">🎪</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-cb-pill bg-cb-semantic-up/10 text-emerald-700 text-xs font-semibold">Paket Bundling</span>
                        </div>
                        <h3 class="text-lg font-semibold text-cb-ink mb-2">Paket Lengkap Event</h3>
                        <p class="text-sm text-cb-body leading-relaxed flex-1">Solusi satu paket untuk kebutuhan identitas acara Anda. Lanyard + ID Card + Card Holder dengan harga spesial.</p>
                        <ul class="mt-4 space-y-1.5 text-sm text-cb-body">
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>Lanyard custom + ID Card PVC</li>
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>Card holder gratis untuk 200+ pcs</li>
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>Pengiriman prioritas 3–5 hari</li>
                        </ul>
                        <a href="/#hubungi" class="mt-6 inline-flex items-center justify-center gap-2 bg-cb-semantic-up text-white text-sm font-semibold px-5 py-3 rounded-cb-pill hover:opacity-90 transition-opacity">
                            Lihat Paket
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Promo 4: First Order --}}
                <div class="group relative flex flex-col rounded-[24px] overflow-hidden border border-cb-hairline hover:border-purple-400/50 hover:shadow-xl transition-all duration-300 bg-white">
                    <div class="h-3 bg-gradient-to-r from-purple-500 to-pink-400"></div>
                    <div class="p-7 flex flex-col flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <span class="text-3xl">🎁</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-cb-pill bg-purple-50 text-purple-700 text-xs font-semibold">Pelanggan Baru</span>
                        </div>
                        <h3 class="text-lg font-semibold text-cb-ink mb-2">Diskon Pesanan Pertama</h3>
                        <p class="text-sm text-cb-body leading-relaxed flex-1">Khusus untuk pelanggan baru, dapatkan potongan harga spesial di pesanan pertama Anda.</p>
                        <ul class="mt-4 space-y-1.5 text-sm text-cb-body">
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-purple-500 shrink-0"></span>Diskon 15% untuk order pertama</li>
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-purple-500 shrink-0"></span>Min. order 50 pcs</li>
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-purple-500 shrink-0"></span>Berlaku semua produk</li>
                        </ul>
                        <a href="/#hubungi" class="mt-6 inline-flex items-center justify-center gap-2 bg-purple-600 text-white text-sm font-semibold px-5 py-3 rounded-cb-pill hover:bg-purple-700 transition-colors">
                            Klaim Diskon
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Promo 5: Referral --}}
                <div class="group relative flex flex-col rounded-[24px] overflow-hidden border border-cb-hairline hover:border-rose-400/50 hover:shadow-xl transition-all duration-300 bg-white">
                    <div class="h-3 bg-gradient-to-r from-rose-500 to-orange-400"></div>
                    <div class="p-7 flex flex-col flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <span class="text-3xl">🤝</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-cb-pill bg-rose-50 text-rose-700 text-xs font-semibold">Program Referral</span>
                        </div>
                        <h3 class="text-lg font-semibold text-cb-ink mb-2">Ajak Teman, Dapat Bonus</h3>
                        <p class="text-sm text-cb-body leading-relaxed flex-1">Rekomendasikan kami ke rekan bisnis Anda dan dapatkan bonus untuk setiap referral yang berhasil memesan.</p>
                        <ul class="mt-4 space-y-1.5 text-sm text-cb-body">
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>Bonus Rp 50.000 per referral</li>
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>Tidak ada batas referral</li>
                            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>Bonus bisa dipakai untuk order berikutnya</li>
                        </ul>
                        <a href="/#hubungi" class="mt-6 inline-flex items-center justify-center gap-2 bg-rose-600 text-white text-sm font-semibold px-5 py-3 rounded-cb-pill hover:bg-rose-700 transition-colors">
                            Daftar Referral
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Promo 6: CTA Card --}}
                <div class="flex flex-col rounded-[24px] overflow-hidden bg-cb-surface-dark text-white p-7 justify-between">
                    <div>
                        <span class="text-4xl block mb-4">💬</span>
                        <h3 class="text-lg font-semibold mb-2">Ada Kebutuhan Khusus?</h3>
                        <p class="text-sm text-white/70 leading-relaxed">Kami terbuka untuk negosiasi harga khusus untuk pesanan korporat, instansi pemerintah, dan event besar.</p>
                    </div>
                    <a href="/#hubungi" class="mt-6 inline-flex items-center justify-center gap-2 bg-white text-cb-ink text-sm font-semibold px-5 py-3 rounded-cb-pill hover:bg-cb-surface-soft transition-colors">
                        Hubungi Kami
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- ── Syarat & Ketentuan ── --}}
    <section class="py-16 bg-cb-surface-soft border-t border-cb-hairline">
        <div class="max-w-[800px] mx-auto px-6">
            <h2 class="text-xl font-semibold text-cb-ink mb-6">Syarat & Ketentuan Promo</h2>
            <ul class="space-y-3 text-sm text-cb-body">
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-cb-surface-strong flex items-center justify-center text-cb-muted text-xs shrink-0 mt-0.5">1</span>
                    Promo tidak dapat digabungkan kecuali disebutkan secara eksplisit.
                </li>
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-cb-surface-strong flex items-center justify-center text-cb-muted text-xs shrink-0 mt-0.5">2</span>
                    Harga promo berlaku untuk pembayaran DP minimal 50% di muka.
                </li>
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-cb-surface-strong flex items-center justify-center text-cb-muted text-xs shrink-0 mt-0.5">3</span>
                    Promo dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya.
                </li>
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-cb-surface-strong flex items-center justify-center text-cb-muted text-xs shrink-0 mt-0.5">4</span>
                    Untuk informasi lebih lanjut, hubungi tim kami melalui WhatsApp atau formulir kontak.
                </li>
            </ul>
        </div>
    </section>

@endsection
