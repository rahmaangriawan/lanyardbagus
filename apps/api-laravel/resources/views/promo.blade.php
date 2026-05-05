@extends('layouts.main')

@section('title', 'Promo & Penawaran Spesial Lanyard Custom - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', 'Promo terbatas lanyard custom, ID card, wristband, dan aksesoris branding. Diskon kuantitas hingga 30%, gratis desain, dan paket bundling event.')

@section('content')

{{-- ── Hero ── --}}
<section class="pt-40 pb-20 bg-cb-canvas border-b border-cb-hairline">
    <div class="max-w-[800px] mx-auto px-6 text-center">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-sm font-semibold tracking-wider uppercase mb-6">
            <span class="nav-promo-fire">🔥</span> Penawaran Terbatas
        </span>
        <h1 class="text-4xl md:text-5xl font-medium text-cb-ink mb-6 tracking-tight">Promo Spesial<br>Bulan Ini</h1>
        <p class="text-base text-cb-body leading-relaxed max-w-xl mx-auto opacity-80">
            Hemat lebih banyak untuk pesanan lanyard, ID card, dan aksesoris branding berkualitas premium. Berlaku selama kuota masih tersedia.
        </p>
    </div>
</section>

{{-- ── Promo Highlight Cards ── --}}
<section class="py-20 bg-cb-canvas">
    <div class="max-w-[1200px] mx-auto px-6">

        <div class="text-center mb-16">
            <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-medium tracking-widest uppercase mb-4">Promo Aktif</span>
            <h2 class="text-3xl font-medium text-cb-ink tracking-tight">Pilih Promo yang Sesuai</h2>
            <p class="text-cb-muted mt-3 text-sm">Semua promo bisa dikombinasikan dengan konsultasi tim kami.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- Card 1: Diskon Kuantitas --}}
            <div class="p-10 rounded-[32px] bg-white shadow-[0_32px_64px_-12px_rgba(0,0,0,0.06)] border border-cb-blue/10 scale-[1.02] z-10 text-center flex flex-col">
                <div class="w-14 h-14 rounded-2xl bg-cb-blue/10 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-7 h-7 text-cb-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-cb-blue uppercase tracking-[0.2em] mb-3 block">Paling Populer</span>
                <h3 class="text-xl font-medium text-cb-ink mb-2">Diskon Kuantitas</h3>
                <div class="py-5">
                    <span class="text-sm text-cb-muted">Hemat hingga</span>
                    <div class="text-4xl font-semibold text-cb-ink mt-1">30%<span class="text-sm font-normal text-cb-muted"> per pcs</span></div>
                </div>
                <p class="text-sm text-cb-body opacity-70 mb-6 flex-1">Semakin banyak pesan, semakin hemat. Berlaku untuk semua jenis produk.</p>
                <div class="space-y-2 text-sm text-cb-body text-left mb-8">
                    <div class="flex items-center justify-between py-2 border-b border-cb-hairline-soft">
                        <span>100–499 pcs</span><span class="font-semibold text-cb-ink">Diskon 10%</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-cb-hairline-soft">
                        <span>500–999 pcs</span><span class="font-semibold text-cb-ink">Diskon 20%</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span>1.000+ pcs</span><span class="font-semibold text-cb-blue">Diskon 30%</span>
                    </div>
                </div>
                <a href="/#hubungi" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-cb-ink text-white text-sm font-medium rounded-cb-pill hover:bg-cb-blue transition-colors shadow-lg">
                    Pesan Sekarang
                </a>
            </div>

            {{-- Card 2: Gratis Desain --}}
            <div class="p-10 rounded-[32px] bg-cb-surface-soft border border-cb-hairline-soft hover:border-cb-blue/20 transition-all duration-500 text-center flex flex-col">
                <div class="w-14 h-14 rounded-2xl bg-cb-accent-yellow/15 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-7 h-7 text-cb-accent-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-cb-muted uppercase tracking-[0.2em] mb-3 block">Gratis</span>
                <h3 class="text-xl font-medium text-cb-ink mb-2">Jasa Desain</h3>
                <div class="py-5">
                    <span class="text-sm text-cb-muted">Nilai hingga</span>
                    <div class="text-4xl font-semibold text-cb-ink mt-1">Rp 0<span class="text-sm font-normal text-cb-muted"> biaya desain</span></div>
                </div>
                <p class="text-sm text-cb-body opacity-70 mb-6 flex-1">Tidak punya file desain? Tim kami siap membantu tanpa biaya tambahan.</p>
                <div class="space-y-2 text-sm text-cb-body text-left mb-8">
                    <div class="flex items-center gap-2 py-1.5">
                        <svg class="w-4 h-4 text-cb-semantic-up shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Min. order 100 pcs
                    </div>
                    <div class="flex items-center gap-2 py-1.5">
                        <svg class="w-4 h-4 text-cb-semantic-up shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Revisi desain hingga 3×
                    </div>
                    <div class="flex items-center gap-2 py-1.5">
                        <svg class="w-4 h-4 text-cb-semantic-up shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Proses 1–2 hari kerja
                    </div>
                </div>
                <a href="/#hubungi" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-cb-surface-strong text-cb-ink text-sm font-medium rounded-cb-pill hover:bg-cb-blue hover:text-white transition-colors">
                    Konsultasi Desain
                </a>
            </div>

            {{-- Card 3: Paket Event --}}
            <div class="p-10 rounded-[32px] bg-cb-surface-soft border border-cb-hairline-soft hover:border-cb-blue/20 transition-all duration-500 text-center flex flex-col">
                <div class="w-14 h-14 rounded-2xl bg-cb-semantic-up/10 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-7 h-7 text-cb-semantic-up" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-cb-muted uppercase tracking-[0.2em] mb-3 block">Bundling</span>
                <h3 class="text-xl font-medium text-cb-ink mb-2">Paket Event</h3>
                <div class="py-5">
                    <span class="text-sm text-cb-muted">Hemat lebih</span>
                    <div class="text-4xl font-semibold text-cb-ink mt-1">15%<span class="text-sm font-normal text-cb-muted"> bundling</span></div>
                </div>
                <p class="text-sm text-cb-body opacity-70 mb-6 flex-1">Lanyard + ID Card + Card Holder dalam satu paket dengan harga spesial.</p>
                <div class="space-y-2 text-sm text-cb-body text-left mb-8">
                    <div class="flex items-center gap-2 py-1.5">
                        <svg class="w-4 h-4 text-cb-semantic-up shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Lanyard custom + ID Card PVC
                    </div>
                    <div class="flex items-center gap-2 py-1.5">
                        <svg class="w-4 h-4 text-cb-semantic-up shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Card holder gratis 200+ pcs
                    </div>
                    <div class="flex items-center gap-2 py-1.5">
                        <svg class="w-4 h-4 text-cb-semantic-up shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Pengiriman prioritas 3–5 hari
                    </div>
                </div>
                <a href="/#hubungi" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-cb-surface-strong text-cb-ink text-sm font-medium rounded-cb-pill hover:bg-cb-blue hover:text-white transition-colors">
                    Lihat Paket
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ── More Promos ── --}}
<section class="py-20 bg-cb-surface-soft border-y border-cb-hairline">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="text-center mb-16">
            <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-medium tracking-widest uppercase mb-4">Lebih Banyak Promo</span>
            <h2 class="text-3xl font-medium text-cb-ink tracking-tight">Keuntungan Tambahan</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="p-8 rounded-2xl bg-white border border-cb-hairline hover:border-cb-blue/20 hover:shadow-md transition-all duration-300">
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center mb-5">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                    </svg>
                </div>
                <h4 class="text-sm font-semibold text-cb-ink mb-2">Diskon Pelanggan Baru</h4>
                <p class="text-sm text-cb-muted leading-relaxed">Potongan 15% untuk pesanan pertama Anda. Min. order 50 pcs, berlaku semua produk.</p>
            </div>

            <div class="p-8 rounded-2xl bg-white border border-cb-hairline hover:border-cb-blue/20 hover:shadow-md transition-all duration-300">
                <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center mb-5">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </div>
                <h4 class="text-sm font-semibold text-cb-ink mb-2">Program Referral</h4>
                <p class="text-sm text-cb-muted leading-relaxed">Ajak rekan bisnis dan dapatkan bonus Rp 50.000 per referral yang berhasil memesan.</p>
            </div>

            <div class="p-8 rounded-2xl bg-white border border-cb-hairline hover:border-cb-blue/20 hover:shadow-md transition-all duration-300">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center mb-5">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                    </svg>
                </div>
                <h4 class="text-sm font-semibold text-cb-ink mb-2">Gratis Ongkir</h4>
                <p class="text-sm text-cb-muted leading-relaxed">Pengiriman gratis ke seluruh Indonesia untuk pesanan di atas 500 pcs. Berlaku Jabodetabek & luar kota.</p>
            </div>

            <div class="p-8 rounded-2xl bg-white border border-cb-hairline hover:border-cb-blue/20 hover:shadow-md transition-all duration-300">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center mb-5">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4 class="text-sm font-semibold text-cb-ink mb-2">Produksi Kilat</h4>
                <p class="text-sm text-cb-muted leading-relaxed">Butuh cepat? Layanan produksi 1–2 hari kerja tersedia untuk pesanan urgent dengan biaya tambahan minimal.</p>
            </div>

        </div>
    </div>
</section>

{{-- ── Syarat & Ketentuan ── --}}
<section class="py-16 bg-cb-canvas">
    <div class="max-w-[800px] mx-auto px-6">
        <h2 class="text-xl font-medium text-cb-ink mb-8">Syarat & Ketentuan Promo</h2>
        <div class="space-y-4">
            @foreach([
                'Promo tidak dapat digabungkan kecuali disebutkan secara eksplisit.',
                'Harga promo berlaku untuk pembayaran DP minimal 50% di muka.',
                'Promo dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya.',
                'Diskon kuantitas dihitung berdasarkan total item dalam satu pesanan.',
                'Untuk negosiasi harga korporat atau instansi, hubungi tim kami langsung.',
            ] as $i => $term)
            <div class="flex items-start gap-4 p-5 rounded-2xl bg-cb-surface-soft border border-cb-hairline-soft">
                <span class="w-6 h-6 rounded-full bg-cb-surface-strong flex items-center justify-center text-cb-muted text-xs font-semibold shrink-0 mt-0.5">{{ $i + 1 }}</span>
                <p class="text-sm text-cb-body leading-relaxed">{{ $term }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ── --}}
<section class="mt-4 mb-24 px-6">
    <div class="max-w-[1200px] mx-auto bg-cb-surface-dark rounded-cb-xl overflow-hidden relative">
        <div class="absolute inset-0 z-0 overflow-hidden rounded-cb-xl pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 bg-cb-blue/15 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        </div>
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 items-center">
            <div class="py-16 md:py-24 pl-8 pr-8 md:pl-16 md:pr-0 text-center md:text-left">
                <h2 class="text-3xl md:text-4xl font-medium text-white mb-6 leading-tight">
                    Ada Kebutuhan Khusus<br class="hidden lg:block"> atau Pesanan Besar?
                </h2>
                <p class="text-cb-muted-soft text-base mb-10 max-w-md mx-auto md:mx-0 leading-relaxed opacity-80">
                    Kami terbuka untuk negosiasi harga khusus untuk pesanan korporat, instansi pemerintah, dan event besar.
                </p>
                <div class="flex justify-center md:justify-start">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '') }}"
                        target="_blank" rel="nofollow noopener noreferrer"
                        class="inline-flex items-center gap-3 px-10 py-4 bg-cb-blue text-white font-medium rounded-cb-pill hover:bg-cb-blue-active transition-all shadow-xl shadow-cb-blue/20">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Hubungi via WhatsApp
                    </a>
                </div>
            </div>
            <div class="relative h-64 md:h-full flex items-end justify-center md:justify-end overflow-hidden">
                <img src="/storage/media/cta-section-ZY9O0.webp" alt="Hubungi Kami"
                    class="md:absolute md:bottom-0 w-auto h-[90%] md:h-[110%] max-w-none object-contain drop-shadow-2xl">
            </div>
        </div>
    </div>
</section>

@endsection
