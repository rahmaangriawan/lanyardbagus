@extends('layouts.main')

@section('title', 'Jenis Tali Lanyard & Material - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', 'Detail material tali lanyard: Tissue dan Polyester. Spesifikasi teknis, lebar tali, dan kualitas cetak untuk kebutuhan identitas Anda.')

@push('head')
    <script type="application/ld+json">
                            {
                              "@@context": "https://schema.org",
                              "@@type": "WebPage",
                              "name": "Jenis Tali Lanyard & Material - {{ $settings->site_title ?? 'Lanyardbagus' }}",
                              "description": "Informasi material tali lanyard: Tissue, Polyester, Nylon, dan Kulit. Lanyardbagus berfokus pada Tissue dan Polyester.",
                              "url": "{{ url('/jenis') }}",
                              "mainEntity": {
                                "@@type": "ItemList",
                                "itemListElement": [
                                  {
                                    "@@type": "ListItem",
                                    "position": 1,
                                    "name": "Lanyard Tissue",
                                    "description": "Material premium halus dengan akurasi cetak tinggi."
                                  },
                                  {
                                    "@@type": "ListItem",
                                    "position": 2,
                                    "name": "Lanyard Polyester",
                                    "description": "Material serat kuat untuk penggunaan lapangan."
                                  }
                                ]
                              }
                            }
                            </script>
    <style>
        /* Specific styles for jenis page if needed */
    </style>
@endpush

@section('content')
    <!-- Hero Section — Centered Design -->
    <section class="pt-40 pb-20 bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[800px] mx-auto px-6 text-center">
            <span
                class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-sm font-semibold tracking-wider uppercase mb-6">Informasi
                Material</span>
            <h1 class="display-lg text-cb-ink mb-6">Material & Spesifikasi Lanyard</h1>
            <p class="text-lg text-cb-body leading-relaxed">
                Panduan lengkap mengenai material kain, teknik cetak, dan ukuran standar untuk membantu Anda memilih lanyard
                yang paling tepat.
            </p>
        </div>
    </section>

    <!-- Priority Notice — Subtle Band -->
    <section class="py-8 bg-cb-surface-soft border-b border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6">
            <div
                class="bg-cb-canvas border border-cb-hairline rounded-cb-lg p-5 flex flex-col md:flex-row items-center gap-6">
                <div
                    class="w-10 h-10 bg-cb-blue rounded-full flex items-center justify-center text-white shrink-0 shadow-lg shadow-cb-blue/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <p class="text-cb-body text-sm">
                        Kami memprioritaskan pengerjaan material <span class="text-cb-blue font-bold">Tissue</span> dan
                        <span class="text-cb-blue font-bold">Polyester</span> karena standar kualitas cetak yang paling
                        konsisten dan tahan lama.
                    </p>
                </div>
                <a href="/#harga"
                    class="px-6 py-2 rounded-cb-pill bg-cb-surface-strong text-cb-ink text-sm font-semibold hover:bg-cb-hairline transition-colors">
                    Lihat Daftar Harga
                </a>
            </div>
        </div>
    </section>

    <!-- Material Details — White Canvas -->
    <section class="py-section bg-cb-canvas">
        <div class="max-w-[1200px] mx-auto px-6">

            <div class="space-y-32">
                <!-- 1. Tissue -->
                <div class="flex flex-col lg:flex-row items-center gap-16">
                    <div class="flex-1 w-full">
                        <div class="relative group">
                            <div
                                class="absolute -inset-4 bg-cb-blue/5 rounded-cb-xl blur-2xl opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                            <div class="relative rounded-cb-xl overflow-hidden border border-cb-hairline shadow-2xl">
                                <img src="/storage/media/aset-lanyard-1-blvlh.webp" alt="Material Tissue Premium"
                                    class="w-full aspect-[4/3] object-cover transition-transform duration-700 group-hover:scale-105">
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="px-4 py-1.5 bg-cb-blue text-white text-xs font-bold tracking-widest uppercase rounded-cb-pill shadow-lg">Premium
                                        Choice</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1">
                        <span class="text-cb-blue font-bold tracking-tighter text-5xl opacity-10 mb-2 block">01</span>
                        <h2 class="display-sm text-cb-ink mb-6">Lanyard Tissue (Premium Sublim)</h2>
                        <div class="space-y-6 text-cb-body leading-relaxed text-base">
                            <p>
                                Bahan Tissue adalah material lanyard kelas tertinggi. Memiliki serat sintetis yang sangat
                                padat dan permukaan yang rata sempurna tanpa tekstur garis-garis, memberikan sensasi sehalus
                                kain sutra di kulit.
                            </p>

                            <div class="grid grid-cols-2 gap-8 py-6 border-y border-cb-hairline">
                                <div>
                                    <h4 class="text-xs font-bold text-cb-muted uppercase tracking-widest mb-2">Lebar
                                        Tersedia</h4>
                                    <p class="text-cb-ink font-bold">1.5cm, 2.0cm, 2.5cm</p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-cb-muted uppercase tracking-widest mb-2">Teknik
                                        Cetak</h4>
                                    <p class="text-cb-ink font-bold">HD Digital Sublimasi</p>
                                </div>
                            </div>

                            <ul class="space-y-4 pt-2">
                                <li class="flex items-start gap-3">
                                    <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-cb-ink shrink-0"></div>
                                    <p><strong class="text-cb-ink">Kenyamanan Maksimal:</strong> Ramah di kulit sensitif,
                                        tidak membuat gatal meski dipakai seharian.</p>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-cb-ink shrink-0"></div>
                                    <p><strong class="text-cb-ink">Akurasi Warna:</strong> Mampu menampilkan gradasi dan
                                        detail desain paling rumit sekalipun.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 2. Polyester -->
                <div class="flex flex-col lg:flex-row-reverse items-center gap-16">
                    <div class="flex-1 w-full">
                        <div class="relative group">
                            <div
                                class="absolute -inset-4 bg-cb-ink/5 rounded-cb-xl blur-2xl opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                            <div class="relative rounded-cb-xl overflow-hidden border border-cb-hairline shadow-2xl">
                                <img src="/storage/media/aset-lanyard-2-S4NP1.webp" alt="Material Polyester Standard"
                                    class="w-full aspect-[4/3] object-cover transition-transform duration-700 group-hover:scale-105">
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="px-4 py-1.5 bg-cb-ink text-white text-xs font-bold tracking-widest uppercase rounded-cb-pill shadow-lg">Durable
                                        Standard</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1">
                        <span class="text-cb-ink font-bold tracking-tighter text-5xl opacity-10 mb-2 block">02</span>
                        <h2 class="display-sm text-cb-ink mb-6">Lanyard Polyester (Standard)</h2>
                        <div class="space-y-6 text-cb-body leading-relaxed text-base">
                            <p>
                                Dikenal karena kekuatannya yang sangat tinggi dan tahan terhadap tarikan ekstrim. Memiliki
                                tekstur anyaman bergaris vertikal (woven) yang khas, sangat cocok untuk penggunaan
                                operasional lapangan atau gudang.
                            </p>

                            <div class="grid grid-cols-2 gap-8 py-6 border-y border-cb-hairline">
                                <div>
                                    <h4 class="text-xs font-bold text-cb-muted uppercase tracking-widest mb-2">Lebar
                                        Tersedia</h4>
                                    <p class="text-cb-ink font-bold">1.5cm, 2.0cm</p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-cb-muted uppercase tracking-widest mb-2">Teknik
                                        Cetak</h4>
                                    <p class="text-cb-ink font-bold">Sablon & Sublimasi</p>
                                </div>
                            </div>

                            <ul class="space-y-4 pt-2">
                                <li class="flex items-start gap-3">
                                    <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-cb-ink shrink-0"></div>
                                    <p><strong class="text-cb-ink">Daya Tahan Ekstra:</strong> Tidak mudah berbulu dan
                                        sangat awet untuk pemakaian kasar.</p>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-cb-ink shrink-0"></div>
                                    <p><strong class="text-cb-ink">Harga Ekonomis:</strong> Solusi identitas profesional
                                        yang sangat efisien untuk pesanan partai besar.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Technical Specs — Gray Elevation Band -->
    <section class="py-section bg-cb-surface-soft border-y border-cb-hairline">
        <div class="max-w-[1000px] mx-auto px-6">
            <div class="text-center mb-16">
                <span
                    class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-sm font-semibold tracking-wider uppercase mb-4">Sisi
                    Teknis</span>
                <h2 class="display-md text-cb-ink mb-4">Tabel Perbandingan Material</h2>
                <p class="text-base text-cb-body max-w-md mx-auto">Informasi mendalam untuk membantu Anda menentukan
                    spesifikasi yang paling sesuai dengan kebutuhan acara atau kantor Anda.</p>
            </div>

            <div class="bg-cb-canvas border border-cb-hairline rounded-cb-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-cb-surface-soft border-b border-cb-hairline">
                                <th class="py-5 px-8 text-sm font-bold text-cb-ink">Kriteria</th>
                                <th class="py-5 px-8 text-sm font-bold text-cb-ink">Material Tissue</th>
                                <th class="py-5 px-8 text-sm font-bold text-cb-ink">Material Polyester</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b border-cb-hairline-soft">
                                <td class="py-5 px-8 text-cb-muted font-medium">Tekstur Permukaan</td>
                                <td class="py-5 px-8 text-cb-ink font-bold">Halus Sempurna (Flat)</td>
                                <td class="py-5 px-8 text-cb-ink font-bold">Anyaman Bergaris (Woven)</td>
                            </tr>
                            <tr class="border-b border-cb-hairline-soft">
                                <td class="py-5 px-8 text-cb-muted font-medium">Ketajaman Cetak</td>
                                <td class="py-5 px-8 text-cb-ink font-bold">High Definition (HD)</td>
                                <td class="py-5 px-8 text-cb-ink font-bold">Standard Definition</td>
                            </tr>
                            <tr class="border-b border-cb-hairline-soft">
                                <td class="py-5 px-8 text-cb-muted font-medium">Ketebalan Tali</td>
                                <td class="py-5 px-8 text-cb-ink font-bold">± 0.8 mm (Tipis Padat)</td>
                                <td class="py-5 px-8 text-cb-ink font-bold">± 1.0 mm (Tebal Kaku)</td>
                            </tr>
                            <tr>
                                <td class="py-5 px-8 text-cb-muted font-medium">Kenyamanan Leher</td>
                                <td class="py-5 px-8 text-cb-ink font-bold">Sangat Nyaman (Soft Feel)</td>
                                <td class="py-5 px-8 text-cb-ink font-bold">Standard (Sturdy Feel)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Alternative Materials — White Editorial -->
    <section class="py-section bg-cb-canvas">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="text-center mb-16 max-w-2xl mx-auto">
                <span
                    class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-ink text-sm font-semibold tracking-wider uppercase mb-4">Opsi
                    Pelengkap</span>
                <h2 class="display-md text-cb-ink">Pilihan Material Eksklusif Lainnya</h2>
                <p class="text-base text-cb-body mt-4">Kami juga menyediakan akses ke material khusus untuk kebutuhan
                    branding yang lebih spesifik atau mewah.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Nylon -->
                <div
                    class="group p-10 bg-cb-canvas border border-cb-hairline rounded-cb-xl hover:border-cb-blue/30 transition-all hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <div
                        class="w-12 h-12 bg-cb-surface-soft rounded-cb-md flex items-center justify-center mb-8 group-hover:bg-cb-blue group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-cb-ink mb-4">Lanyard Nylon (Glossy)</h3>
                    <p class="text-cb-body leading-relaxed text-sm">
                        Material dengan kilau alami yang sangat formal. Serat nylon memberikan tampilan yang lebih "licin"
                        dan mencolok. Sangat populer untuk penggunaan event resmi kenegaraan atau perusahaan multinasional
                        yang mengejar kesan eksklusif yang berani.
                    </p>
                </div>
                <!-- Leather -->
                <div
                    class="group p-10 bg-cb-canvas border border-cb-hairline rounded-cb-xl hover:border-cb-ink/30 transition-all hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <div
                        class="w-12 h-12 bg-cb-surface-soft rounded-cb-md flex items-center justify-center mb-8 group-hover:bg-cb-ink group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-cb-ink mb-4">Lanyard Kulit (Exclusive)</h3>
                    <p class="text-cb-body leading-relaxed text-sm">
                        Simbol kemewahan sejati. Menggunakan kulit sintetis premium atau kulit asli dengan logo yang
                        diaplikasikan melalui teknik grafir laser atau emboss panas. Opsi terbaik untuk direksi, tamu VIP,
                        atau sebagai item hadiah perusahaan kelas atas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action — Dark Section -->
    <section class="mt-40 mb-24 px-6">
        <div class="max-w-[1200px] mx-auto bg-cb-surface-dark rounded-cb-xl overflow-hidden md:overflow-visible relative">
            <!-- Background Decoration -->
            <div class="absolute inset-0 z-0 overflow-hidden rounded-cb-xl pointer-events-none">
                <div class="absolute top-0 right-0 w-96 h-96 bg-cb-blue/15 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            </div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 items-center">
                <!-- Column 1: Text -->
                <div class="py-16 md:py-24 pl-8 pr-8 md:pl-16 md:pr-0 text-center md:text-left">
                    <h2 class="display-md text-white mb-6 leading-tight">
                        Konsultasi Material <br class="hidden lg:block"> Lanyard Gratis
                    </h2>
                    <p class="text-cb-muted-soft text-lg mb-10 max-w-md mx-auto md:mx-0 leading-relaxed">
                        Masih ragu memilih bahan? Tim kami siap memberikan rekomendasi terbaik sesuai logo dan budget Anda.
                    </p>
                    <div class="flex justify-center md:justify-start">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '') }}"
                            target="_blank" rel="nofollow noopener noreferrer"
                            class="inline-flex items-center gap-3 px-10 py-4 bg-cb-blue text-white font-bold rounded-cb-pill hover:bg-cb-blue-active transition-all shadow-xl shadow-cb-blue/20">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            Konsultasi via WhatsApp
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

@push('scripts')
    <script>
        // Optional: Any specific JS for jenis page
    </script>
@endpush