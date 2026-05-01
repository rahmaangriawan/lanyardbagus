@extends('layouts.main')

@section('title', 'Katalog Produk Tali Lanyard - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', 'Koleksi lengkap tali lanyard custom: ukuran 1.5cm, 2cm, 2.5cm, dan opsi cetak 2 sisi. Temukan spesifikasi terbaik untuk brand Anda.')

@section('content')
    <!-- Hero Section — Clean & Minimalist -->
    <section class="pt-40 pb-24 bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[800px] mx-auto px-6 text-center">
            <span
                class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-sm font-semibold tracking-wider uppercase mb-6">Katalog
                Produk</span>
            <h1 class="text-4xl md:text-5xl font-medium text-cb-ink mb-6 tracking-tight">Dimensi Tepat Untuk Identitas Anda
            </h1>
            <p class="text-base text-cb-body leading-relaxed max-w-2xl mx-auto">
                Temukan spesifikasi lanyard yang dirancang khusus untuk memperkuat karakter brand Anda secara profesional
                dan presisi.
            </p>
        </div>
    </section>

    <!-- Product Sequence — High-End Vertical Showcase -->
    <section class="bg-cb-canvas">
        <div class="max-w-[1100px] mx-auto px-6">

            <!-- Item 01: 1.5 cm -->
            <div class="py-24 border-b border-cb-hairline group relative overflow-hidden">
                <div
                    class="absolute -right-10 top-1/2 -translate-y-1/2 text-[16rem] font-semibold text-cb-surface-strong/30 select-none pointer-events-none z-0">
                    01</div>
                <div class="relative z-10 flex flex-col lg:flex-row items-center gap-16">
                    <div class="flex-1 w-full">
                        <div
                            class="rounded-3xl overflow-hidden border border-cb-hairline transition-transform duration-700 group-hover:scale-[1.01]">
                            <img src="/storage/media/lanyard-1-setengah-senti-j7tlz.webp" alt="Lanyard 1.5cm"
                                class="w-full aspect-[4/3] object-cover">
                        </div>
                    </div>
                    <div class="flex-1">
                        <span
                            class="inline-flex px-3 py-1 bg-cb-blue/5 text-cb-blue text-xs font-medium tracking-widest uppercase rounded-cb-pill mb-6">Minimalist
                            Edition</span>
                        <h2 class="text-3xl font-medium text-cb-ink mb-6 tracking-tight">Lanyard Slim 1.5 cm</h2>
                        <p class="text-cb-body text-base leading-relaxed mb-10 opacity-80">
                            Solusi cerdas bagi brand yang mengutamakan tampilan subtle dan ringan. Ukuran ramping ini
                            memberikan kesan profesional yang tidak berlebihan.
                        </p>
                        <!-- Integrated Mini Specs -->
                        <div class="grid grid-cols-2 gap-y-6 gap-x-12 border-t border-cb-hairline pt-8">
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Lebar Kain
                                </h4>
                                <p class="text-cb-ink text-base">1.5 cm</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Metode
                                    Cetak</h4>
                                <p class="text-cb-ink text-base">Sublim / Sablon</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Karakter
                                </h4>
                                <p class="text-cb-ink text-base">Ringan & Ramping</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Aksesori
                                </h4>
                                <p class="text-cb-ink text-base">Kait / Stopper</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 02: 2.0 cm -->
            <div class="py-24 border-b border-cb-hairline group relative overflow-hidden">
                <div
                    class="absolute -right-10 top-1/2 -translate-y-1/2 text-[16rem] font-semibold text-cb-surface-strong/30 select-none pointer-events-none z-0">
                    02</div>
                <div class="relative z-10 flex flex-col lg:flex-row items-center gap-16">
                    <div class="flex-1 w-full lg:order-2">
                        <div
                            class="rounded-3xl overflow-hidden border border-cb-hairline transition-transform duration-700 group-hover:scale-[1.01]">
                            <img src="/storage/media/lanyard-2-cm-uKPjV.webp" alt="Lanyard 2.0cm"
                                class="w-full aspect-[4/3] object-cover">
                        </div>
                    </div>
                    <div class="flex-1 lg:order-1">
                        <span
                            class="inline-flex px-3 py-1 bg-cb-blue/5 text-cb-blue text-xs font-medium tracking-widest uppercase rounded-cb-pill mb-6">Most
                            Popular</span>
                        <h2 class="text-3xl font-medium text-cb-ink mb-6 tracking-tight">Lanyard Standard 2.0 cm</h2>
                        <p class="text-cb-body text-base leading-relaxed mb-10 opacity-80">
                            Merupakan standar industri yang paling diminati. Ukuran 2 cm memberikan keseimbangan sempurna
                            antara area branding dan kenyamanan maksimal.
                        </p>
                        <!-- Integrated Mini Specs -->
                        <div class="grid grid-cols-2 gap-y-6 gap-x-12 border-t border-cb-hairline pt-8">
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Lebar Kain
                                </h4>
                                <p class="text-cb-ink text-base">2.0 cm</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Metode
                                    Cetak</h4>
                                <p class="text-cb-ink text-base">Sublim / Sablon</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Karakter
                                </h4>
                                <p class="text-cb-ink text-base">Seimbang & Universal</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Aksesori
                                </h4>
                                <p class="text-cb-ink text-base">Full Accessories</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 03: 2.5 cm -->
            <div class="py-24 border-b border-cb-hairline group relative overflow-hidden">
                <div
                    class="absolute -right-10 top-1/2 -translate-y-1/2 text-[16rem] font-semibold text-cb-surface-strong/30 select-none pointer-events-none z-0">
                    03</div>
                <div class="relative z-10 flex flex-col lg:flex-row items-center gap-16">
                    <div class="flex-1 w-full">
                        <div
                            class="rounded-3xl overflow-hidden border border-cb-hairline transition-transform duration-700 group-hover:scale-[1.01]">
                            <img src="/storage/media/lanyard-2-setengah-senti-5WhBG.webp" alt="Lanyard 2.5cm"
                                class="w-full aspect-[4/3] object-cover">
                        </div>
                    </div>
                    <div class="flex-1">
                        <span
                            class="inline-flex px-3 py-1 bg-cb-blue/5 text-cb-blue text-xs font-medium tracking-widest uppercase rounded-cb-pill mb-6">Premium
                            Impact</span>
                        <h2 class="text-3xl font-medium text-cb-ink mb-6 tracking-tight">Lanyard Wide 2.5 cm</h2>
                        <p class="text-cb-body text-base leading-relaxed mb-10 opacity-80">
                            Didesain khusus untuk dominasi brand yang kuat. Area cetak yang paling lebar memungkinkan detail
                            desain yang kompleks terlihat tajam.
                        </p>
                        <!-- Integrated Mini Specs -->
                        <div class="grid grid-cols-2 gap-y-6 gap-x-12 border-t border-cb-hairline pt-8">
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Lebar Kain
                                </h4>
                                <p class="text-cb-ink text-base">2.5 cm</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Metode
                                    Cetak</h4>
                                <p class="text-cb-ink text-base">Sublim Full Color</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Karakter
                                </h4>
                                <p class="text-cb-ink text-base">Kuat & Maksimal</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Aksesori
                                </h4>
                                <p class="text-cb-ink text-base">Full Accessories</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 04: 2 Sisi -->
            <div id="2sisi" class="py-24 border-b border-cb-hairline group relative overflow-hidden">
                <div
                    class="absolute -right-10 top-1/2 -translate-y-1/2 text-[16rem] font-semibold text-cb-surface-strong/30 select-none pointer-events-none z-0">
                    04</div>
                <div class="relative z-10 flex flex-col lg:flex-row items-center gap-16">
                    <div class="flex-1 w-full lg:order- order-2">
                        <div
                            class="rounded-3xl overflow-hidden border border-cb-hairline transition-transform duration-700 group-hover:scale-[1.01]">
                            <img src="/storage/media/lanyard-2-sisi-iCca6.webp" alt="Lanyard 2 Sisi"
                                class="w-full aspect-[4/3] object-cover">
                        </div>
                    </div>
                    <div class="flex-1 lg:order-1 order-1">
                        <span
                            class="inline-flex px-3 py-1 bg-cb-blue/5 text-cb-blue text-xs font-medium tracking-widest uppercase rounded-cb-pill mb-6">360
                            Branding</span>
                        <h2 class="text-3xl font-medium text-cb-ink mb-6 tracking-tight">Cetak Bolak Balik (2 Sisi)</h2>
                        <p class="text-cb-body text-base leading-relaxed mb-10 opacity-80">
                            Maksimalkan setiap inci ruang yang tersedia. Dengan opsi cetak 2 sisi, brand Anda akan selalu
                            terlihat sempurna dari segala sudut.
                        </p>
                        <!-- Integrated Mini Specs -->
                        <div class="grid grid-cols-2 gap-y-6 gap-x-12 border-t border-cb-hairline pt-8">
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Metode
                                </h4>
                                <p class="text-cb-ink text-base">Sublimasi Bolak-Balik</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Registrasi
                                </h4>
                                <p class="text-cb-ink text-base">Presisi Tinggi</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Visual
                                </h4>
                                <p class="text-cb-ink text-base">Tanpa Sisi Kosong</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">
                                    Rekomendasi</h4>
                                <p class="text-cb-ink text-base">2.0 - 2.5 cm</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item 05: Paket Identitas -->
            <div id="paketidentitas" class="py-24 group relative overflow-hidden">
                <div
                    class="absolute -right-10 top-1/2 -translate-y-1/2 text-[16rem] font-semibold text-cb-surface-strong/30 select-none pointer-events-none z-0">
                    05</div>
                <div class="relative z-10 flex flex-col lg:flex-row items-center gap-16">
                    <div class="flex-1 w-full">
                        <div
                            class="rounded-3xl overflow-hidden border border-cb-hairline transition-transform duration-700 group-hover:scale-[1.01]">
                            <img src="/storage/media/paket-lanyard-id-card-BoSmK.webp" alt="Paket Identitas"
                                class="w-full aspect-[4/3] object-cover">
                        </div>
                    </div>
                    <div class="flex-1">
                        <span
                            class="inline-flex px-3 py-1 bg-cb-blue/5 text-cb-blue text-xs font-medium tracking-widest uppercase rounded-cb-pill mb-6">Complete
                            Solution</span>
                        <h2 class="text-3xl font-medium text-cb-ink mb-6 tracking-tight">Paket Identitas (Lanyard + ID Card)
                        </h2>
                        <p class="text-cb-body text-base leading-relaxed mb-10 opacity-80">
                            Harmonisasi sempurna untuk identitas tim Anda. Paket lengkap yang menyertakan tali lanyard
                            custom berkualitas tinggi dan kartu identitas (ID Card) dengan desain yang senada.
                        </p>
                        <!-- Integrated Mini Specs -->
                        <div class="grid grid-cols-2 gap-y-6 gap-x-12 border-t border-cb-hairline pt-8">
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Isi Paket
                                </h4>
                                <p class="text-cb-ink text-base">Lanyard + ID Card</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">ID Card
                                    Material
                                </h4>
                                <p class="text-cb-ink text-base">PVC Premium 0.76mm</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Cetak Card
                                </h4>
                                <p class="text-cb-ink text-base">Offset / Digital HD</p>
                            </div>
                            <div>
                                <h4 class="text-xs font-normal text-cb-muted uppercase tracking-[0.2em] mb-1">Casing
                                </h4>
                                <p class="text-cb-ink text-base">Opsional (Silikon/Mika)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Comparison Table (PRESERVED) -->
    <section class="py-section bg-cb-surface-soft border-y border-cb-hairline">
        <div class="max-w-[1000px] mx-auto px-6">
            <div class="text-center mb-16">
                <span
                    class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-sm font-semibold tracking-wider uppercase mb-4">Sisi
                    Teknis</span>
                <h2 class="display-md text-cb-ink">Perbandingan Detail Produk</h2>
            </div>
            <div class="bg-cb-canvas border border-cb-hairline rounded-cb-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-cb-surface-strong/50 border-b border-cb-hairline">
                                <th class="py-5 px-8 text-sm font-bold text-cb-muted uppercase tracking-wider">Kategori</th>
                                <th class="py-5 px-8 text-sm font-bold text-cb-muted uppercase tracking-wider">Lebar Kain
                                </th>
                                <th class="py-5 px-8 text-sm font-bold text-cb-muted uppercase tracking-wider">Metode Cetak
                                </th>
                                <th class="py-5 px-8 text-sm font-bold text-cb-muted uppercase tracking-wider">Aksesori</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b border-cb-hairline-soft">
                                <td class="py-5 px-8 font-bold text-cb-ink">Lanyard Slim</td>
                                <td class="py-5 px-8 text-cb-body">1.5 cm</td>
                                <td class="py-5 px-8 text-cb-body">Sublim / Sablon</td>
                                <td class="py-5 px-8 text-cb-body">Kait / Stopper</td>
                            </tr>
                            <tr class="border-b border-cb-hairline-soft">
                                <td class="py-5 px-8 font-bold text-cb-ink">Lanyard Standard</td>
                                <td class="py-5 px-8 text-cb-body">2.0 cm</td>
                                <td class="py-5 px-8 text-cb-body">Sublim / Sablon</td>
                                <td class="py-5 px-8 text-cb-body">Full Accessories</td>
                            </tr>
                            <tr class="border-b border-cb-hairline-soft">
                                <td class="py-5 px-8 font-bold text-cb-ink">Lanyard Wide</td>
                                <td class="py-5 px-8 text-cb-body">2.5 cm</td>
                                <td class="py-5 px-8 text-cb-body">Sublim HD</td>
                                <td class="py-5 px-8 text-cb-body">Full Accessories</td>
                            </tr>
                            <tr class="border-b border-cb-hairline-soft">
                                <td class="py-5 px-8 font-bold text-cb-ink">Lanyard 2 Sisi</td>
                                <td class="py-5 px-8 text-cb-body">2.0 - 2.5 cm</td>
                                <td class="py-5 px-8 text-cb-body">HD Sublimation</td>
                                <td class="py-5 px-8 text-cb-body">Premium Hook</td>
                            </tr>
                            <tr>
                                <td class="py-5 px-8 font-bold text-cb-ink">Paket Identitas</td>
                                <td class="py-5 px-8 text-cb-body">Custom</td>
                                <td class="py-5 px-8 text-cb-body">Lanyard + ID Card</td>
                                <td class="py-5 px-8 text-cb-body">ID Holder Include</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section — Classic Design (RESTORED) -->
    <section class="mt-20 mb-24 px-6">
        <div class="max-w-[1200px] mx-auto bg-cb-surface-dark rounded-cb-xl overflow-hidden md:overflow-visible relative">
            <!-- Background Decoration -->
            <div class="absolute inset-0 z-0 overflow-hidden rounded-cb-xl pointer-events-none">
                <div class="absolute top-0 right-0 w-96 h-96 bg-cb-blue/15 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            </div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 items-center">
                <!-- Column 1: Text -->
                <div class="py-16 md:py-24 pl-8 pr-8 md:pl-16 md:pr-0 text-center md:text-left">
                    <h2 class="display-md text-white mb-6 leading-tight">
                        Konsultasi Ukuran <br class="hidden lg:block"> & Desain Gratis
                    </h2>
                    <p class="text-cb-muted-soft text-lg mb-10 max-w-md mx-auto md:mx-0 leading-relaxed">
                        Bingung menentukan ukuran yang paling pas untuk brand Anda? Tim kami siap membantu memberikan
                        simulasi desain terbaik.
                    </p>
                    <div class="flex justify-center md:justify-start">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '') }}"
                            target="_blank" rel="nofollow noopener noreferrer"
                            class="inline-flex items-center gap-3 px-10 py-4 bg-cb-blue text-white font-medium rounded-cb-pill hover:bg-cb-blue-active transition-all shadow-xl shadow-cb-blue/20">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            Hubungi via WhatsApp
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