@extends('layouts.main')

@section('title', 'Daftar Harga Tali Lanyard Custom - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', 'Penawaran harga terbaik untuk tali lanyard custom 1.5cm, 2cm, dan 2.5cm. Transparan, kompetitif, dan tersedia diskon kuantitas untuk kebutuhan korporat Anda.')

@section('content')
    <!-- Hero Section — Clean & Airy -->
    <section class="pt-40 pb-20 bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[800px] mx-auto px-6 text-center">
            <span
                class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-sm font-semibold tracking-wider uppercase mb-6">Investasi
                Brand</span>
            <h1 class="text-4xl md:text-5xl font-medium text-cb-ink mb-6 tracking-tight">Transparansi Harga Untuk Kualitas
                Terbaik</h1>
            <p class="text-base text-cb-body leading-relaxed max-w-2xl mx-auto opacity-80">
                Kami percaya pada keterbukaan. Temukan struktur harga kompetitif kami yang dirancang untuk memberikan nilai
                maksimal bagi identitas profesional Anda.
            </p>
        </div>
    </section>

    <!-- Pricing Summary Cards -->
    <section class="py-20 bg-cb-canvas">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- 1.5 cm -->
                <div
                    class="p-10 rounded-[32px] bg-cb-surface-soft border border-cb-hairline-soft hover:border-cb-blue/20 transition-all duration-500 text-center">
                    <span class="text-xs font-medium text-cb-muted uppercase tracking-[0.2em] mb-4 block">Lanyard
                        Slim</span>
                    <h3 class="text-2xl font-medium text-cb-ink mb-2">Ukuran 1.5 cm</h3>
                    <div class="py-6">
                        <span class="text-sm text-cb-muted">Mulai dari</span>
                        <div class="text-4xl font-semibold text-cb-ink mt-1">Rp 8.500<span
                                class="text-sm font-normal text-cb-muted">/pcs</span></div>
                    </div>
                    <p class="text-sm text-cb-body opacity-70 mb-8">Ideal untuk start-up dan event dengan budget efisien
                        namun tetap premium.</p>
                    <div class="pt-6 border-t border-cb-hairline-soft">
                        <span class="text-xs font-medium text-cb-blue uppercase tracking-widest">Paling Ekonomis</span>
                    </div>
                </div>

                <!-- 2.0 cm -->
                <div
                    class="p-10 rounded-[32px] bg-white shadow-[0_32px_64px_-12px_rgba(0,0,0,0.06)] border border-cb-blue/10 scale-105 z-10 text-center">
                    <span class="text-xs font-medium text-cb-blue uppercase tracking-[0.2em] mb-4 block">Best
                        Seller</span>
                    <h3 class="text-2xl font-medium text-cb-ink mb-2">Ukuran 2.0 cm</h3>
                    <div class="py-6">
                        <span class="text-sm text-cb-muted">Mulai dari</span>
                        <div class="text-4xl font-semibold text-cb-ink mt-1">Rp 9.500<span
                                class="text-sm font-normal text-cb-muted">/pcs</span></div>
                    </div>
                    <p class="text-sm text-cb-body opacity-70 mb-8">Standar korporat yang memberikan keseimbangan terbaik
                        antara harga dan visibilitas.</p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '') }}"
                        target="_blank" rel="nofollow noopener noreferrer"
                        class="inline-flex px-8 py-3 bg-cb-ink text-white text-sm font-medium rounded-cb-pill hover:bg-cb-blue transition-colors shadow-lg">Pesan
                        Sekarang</a>
                </div>

                <!-- 2.5 cm -->
                <div
                    class="p-10 rounded-[32px] bg-cb-surface-soft border border-cb-hairline-soft hover:border-cb-blue/20 transition-all duration-500 text-center">
                    <span class="text-xs font-medium text-cb-muted uppercase tracking-[0.2em] mb-4 block">Lanyard
                        Wide</span>
                    <h3 class="text-2xl font-medium text-cb-ink mb-2">Ukuran 2.5 cm</h3>
                    <div class="py-6">
                        <span class="text-sm text-cb-muted">Mulai dari</span>
                        <div class="text-4xl font-semibold text-cb-ink mt-1">Rp 11.500<span
                                class="text-sm font-normal text-cb-muted">/pcs</span></div>
                    </div>
                    <p class="text-sm text-cb-body opacity-70 mb-8">Opsi lebar untuk impact maksimal dan detail desain yang
                        paling tajam.</p>
                    <div class="pt-6 border-t border-cb-hairline-soft">
                        <span class="text-xs font-medium text-cb-muted uppercase tracking-widest">Impact Maksimal</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Pricing Table -->
    <section class="py-20 bg-cb-surface-soft border-y border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="text-center mb-16">
                <span
                    class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-medium tracking-widest uppercase mb-4">Volume
                    Discount</span>
                <h2 class="text-3xl font-medium text-cb-ink tracking-tight">Tabel Harga Berdasarkan Kuantitas</h2>
                <p class="text-cb-muted mt-4">Semakin banyak pesanan Anda, semakin terjangkau harga per satuannya.</p>
            </div>

            <div class="bg-white border border-cb-hairline rounded-[32px] overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-cb-surface-soft border-b border-cb-hairline">
                                <th
                                    class="py-6 px-8 text-xs font-medium text-cb-muted uppercase tracking-[0.2em] whitespace-nowrap">
                                    Kuantitas</th>
                                <th
                                    class="py-6 px-8 text-xs font-medium text-cb-muted uppercase tracking-[0.2em] whitespace-nowrap">
                                    1.5 cm</th>
                                <th
                                    class="py-6 px-8 text-xs font-medium text-cb-muted uppercase tracking-[0.2em] whitespace-nowrap">
                                    2.0 cm</th>
                                <th
                                    class="py-6 px-8 text-xs font-medium text-cb-muted uppercase tracking-[0.2em] whitespace-nowrap">
                                    2.5 cm</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b border-cb-hairline-soft group hover:bg-cb-surface-soft transition-colors">
                                <td class="py-8 px-8 text-cb-body font-medium whitespace-nowrap">10 - 50 pcs</td>
                                <td class="py-8 px-8 text-cb-ink whitespace-nowrap">Rp 12.500</td>
                                <td class="py-8 px-8 text-cb-ink whitespace-nowrap">Rp 13.500</td>
                                <td class="py-8 px-8 text-cb-ink whitespace-nowrap">Rp 15.500</td>
                            </tr>
                            <tr class="border-b border-cb-hairline-soft group hover:bg-cb-surface-soft transition-colors">
                                <td class="py-8 px-8 text-cb-body font-medium whitespace-nowrap">51 - 100 pcs</td>
                                <td class="py-8 px-8 text-cb-ink whitespace-nowrap">Rp 10.500</td>
                                <td class="py-8 px-8 text-cb-ink whitespace-nowrap">Rp 11.500</td>
                                <td class="py-8 px-8 text-cb-ink whitespace-nowrap">Rp 13.500</td>
                            </tr>
                            <tr class="border-b border-cb-hairline-soft group hover:bg-cb-surface-soft transition-colors">
                                <td class="py-8 px-8 text-cb-body font-medium whitespace-nowrap">101 - 500 pcs</td>
                                <td class="py-8 px-8 text-cb-ink whitespace-nowrap">Rp 9.000</td>
                                <td class="py-8 px-8 text-cb-ink whitespace-nowrap">Rp 10.000</td>
                                <td class="py-8 px-8 text-cb-ink whitespace-nowrap">Rp 12.000</td>
                            </tr>
                            <tr class="group hover:bg-cb-surface-soft transition-colors">
                                <td class="py-8 px-8 text-cb-blue font-bold whitespace-nowrap">501+ pcs</td>
                                <td class="py-8 px-8 text-cb-ink font-semibold whitespace-nowrap">Rp 8.500</td>
                                <td class="py-8 px-8 text-cb-ink font-semibold whitespace-nowrap">Rp 9.500</td>
                                <td class="py-8 px-8 text-cb-ink font-semibold whitespace-nowrap">Rp 11.500</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-6 px-8">
                <div class="flex items-center gap-3 text-sm text-cb-muted">
                    <svg class="w-4 h-4 text-cb-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Harga sudah termasuk cetak sublimasi full color (1 atau 2 sisi).
                </div>
                <div class="flex items-center gap-3 text-sm text-cb-muted">
                    <svg class="w-4 h-4 text-cb-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Waktu produksi standar: 3-5 hari kerja.
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Options -->
    <section class="py-20 bg-cb-canvas">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-medium text-cb-ink tracking-tight">Opsi Pelengkap</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-8 rounded-2xl bg-cb-surface-soft border border-cb-hairline-soft">
                    <h4 class="text-sm font-medium text-cb-ink mb-2">Stopper / Sambungan</h4>
                    <p class="text-sm text-cb-muted leading-relaxed">Tambahan Rp 500/pcs untuk kemudahan lepas-pasang
                        lanyard.</p>
                </div>
                <div class="p-8 rounded-2xl bg-cb-surface-soft border border-cb-hairline-soft">
                    <h4 class="text-sm font-medium text-cb-ink mb-2">Kait Premium</h4>
                    <p class="text-sm text-cb-muted leading-relaxed">Upgrade ke kait besi yang lebih tebal dan kokoh (+ Rp
                        1.000/pcs).</p>
                </div>
                <div class="p-8 rounded-2xl bg-cb-surface-soft border border-cb-hairline-soft">
                    <h4 class="text-sm font-medium text-cb-ink mb-2">Card Holder</h4>
                    <p class="text-sm text-cb-muted leading-relaxed">Casing ID Card bahan plastik atau kulit imitasi mulai
                        Rp 2.000/pcs.</p>
                </div>
                <div class="p-8 rounded-2xl bg-cb-surface-soft border border-cb-hairline-soft">
                    <h4 class="text-sm font-medium text-cb-ink mb-2">Custom Package</h4>
                    <p class="text-sm text-cb-muted leading-relaxed">Packaging plastik satuan atau box eksklusif (Hubungi
                        kami).</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section — Classic Design -->
    <section class="mt-20 mb-24 px-6">
        <div class="max-w-[1200px] mx-auto bg-cb-surface-dark rounded-cb-xl overflow-hidden md:overflow-visible relative">
            <!-- Background Decoration -->
            <div class="absolute inset-0 z-0 overflow-hidden rounded-cb-xl pointer-events-none">
                <div class="absolute top-0 right-0 w-96 h-96 bg-cb-blue/15 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            </div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 items-center">
                <!-- Column 1: Text -->
                <div class="py-16 md:py-24 pl-8 pr-8 md:pl-16 md:pr-0 text-center md:text-left">
                    <h2 class="text-3xl md:text-4xl font-medium text-white mb-6 leading-tight">
                        Dapatkan Penawaran <br class="hidden lg:block"> Harga Khusus Hari Ini
                    </h2>
                    <p class="text-cb-muted-soft text-base mb-10 max-w-md mx-auto md:mx-0 leading-relaxed opacity-80">
                        Punya pesanan dalam jumlah besar atau butuh spesifikasi khusus? Konsultasikan dengan tim kami untuk
                        mendapatkan harga terbaik.
                    </p>
                    <div class="flex justify-center md:justify-start">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '') }}"
                            target="_blank" rel="nofollow noopener noreferrer"
                            class="inline-flex items-center gap-3 px-10 py-4 bg-cb-blue text-white font-medium rounded-cb-pill hover:bg-cb-blue-active transition-all shadow-xl shadow-cb-blue/20">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            WhatsApp
                        </a>
                    </div>
                </div>

                <!-- Column 2: Image with Overflow -->
                <div class="relative h-72 md:h-full flex items-end justify-center md:justify-end">
                    <img src="/storage/media/cta-section-ZY9O0.webp" alt="CTA Support"
                        class="md:absolute md:bottom-0 md:-right-0 w-auto h-[105%] md:h-[115%] max-w-none object-contain drop-shadow-2xl z-20">
                </div>
            </div>
        </div>
    </section>
@endsection