@extends('layouts.main')

@section('title', 'FAQ - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', 'Temukan jawaban atas pertanyaan umum tentang pemesanan lanyard custom, bahan, desain, dan pengiriman. Kami siap membantu Anda 24/7.')

@section('content')
    @php
        $faqData = $settings->faq_data ?? [];
        $hero = data_get($faqData, 'hero', []);
        $categories = data_get($faqData, 'categories', [
            [
                'title' => 'Pemesanan & Minimum Order',
                'icon' => 'shopping-cart',
                'items' => [
                    [
                        'question' => 'Berapa minimum pemesanan lanyard custom?',
                        'answer' => 'Kami menerima pemesanan dalam jumlah fleksibel. Untuk mendapatkan harga terbaik dan efisiensi produksi, kami merekomendasikan minimum order sebanyak 50 pcs. Namun, jangan ragu untuk berkonsultasi jika kebutuhan Anda di bawah jumlah tersebut.'
                    ],
                    [
                        'question' => 'Bagaimana cara memesan lanyard custom?',
                        'answer' => 'Anda dapat memesan melalui formulir pemesanan di website kami, WhatsApp, atau email. Tim kami akan menghubungi Anda untuk konfirmasi desain, harga, dan jadwal pengiriman.'
                    ],
                    [
                        'question' => 'Apakah ada biaya desain?',
                        'answer' => 'Tidak! Layanan desain dasar kami berikan secara gratis untuk pelanggan yang telah melakukan pembayaran uang muka. Untuk desain kompleks, tim kami akan menginformasikan biaya tambahan sebelumnya.'
                    ],
                ]
            ],
            [
                'title' => 'Bahan & Kualitas',
                'icon' => 'sparkles',
                'items' => [
                    [
                        'question' => 'Bahan apa yang paling direkomendasikan?',
                        'answer' => 'Untuk hasil cetak paling tajam dengan kenyamanan maksimal, kami sangat menyarankan bahan Tissue (Polyester halus) dipadukan dengan teknik Printing Premium. Bahan ini sangat lembut di leher dan mampu menyerap tinta sublimasi dengan sempurna.'
                    ],
                    [
                        'question' => 'Apakah warna lanyard akan luntur?',
                        'answer' => 'Tidak. Kami menggunakan teknologi printing sublimasi dengan tinta premium yang tahan lama. Hasil cetakan tidak akan luntur meski dicuci berulang kali atau terpapar sinar matahari.'
                    ],
                    [
                        'question' => 'Berapa lama lanyard bisa bertahan?',
                        'answer' => 'Dengan perawatan normal, lanyard kami dapat bertahan 2-3 tahun atau lebih. Bahan polyester premium kami tahan terhadap stretch, tidak mudah robek, dan tetap nyaman dipakai seharian.'
                    ],
                ]
            ],
            [
                'title' => 'Produksi & Pengiriman',
                'icon' => 'truck',
                'items' => [
                    [
                        'question' => 'Berapa lama waktu pengerjaan produksi?',
                        'answer' => 'Standar waktu produksi kami berkisar antara 3 hingga 7 hari kerja tergantung dari jumlah pesanan dan antrean produksi. Kami juga menyediakan layanan prioritas (express) untuk kebutuhan mendesak dengan biaya tambahan.'
                    ],
                    [
                        'question' => 'Apakah bisa pengiriman ke seluruh Indonesia?',
                        'answer' => 'Ya! Kami mengirim ke seluruh Indonesia menggunakan jasa kurir terpercaya seperti JNE, J&T, dan SiCepat. Untuk pengiriman besar, kami juga dapat menggunakan cargo untuk efisiensi biaya.'
                    ],
                    [
                        'question' => 'Berapa lama pengiriman?',
                        'answer' => 'Waktu pengiriman tergantung lokasi: Jabodetabek 1-2 hari, Jawa 2-4 hari, dan luar Jawa 4-7 hari. Anda akan mendapatkan nomor resi untuk tracking pengiriman.'
                    ],
                ]
            ],
            [
                'title' => 'Pembayaran',
                'icon' => 'credit-card',
                'items' => [
                    [
                        'question' => 'Metode pembayaran apa yang diterima?',
                        'answer' => 'Kami menerima transfer bank (BCA, Mandiri, BNI, BRI), e-wallet (GoPay, OVO, DANA, LinkAja), dan QRIS. Untuk order besar, tersedia opsi cicilan atau pembayaran bertahap.'
                    ],
                    [
                        'question' => 'Apakah perlu uang muka?',
                        'answer' => 'Ya, untuk memulai produksi kami memerlukan uang muka minimal 50% dari total pesanan. Sisanya dapat dibayar setelah produksi selesai dan sebelum pengiriman.'
                    ],
                    [
                        'question' => 'Apakah ada garansi?',
                        'answer' => 'Ya! Kami memberikan garansi 100% penggantian jika ada kesalahan produksi dari pihak kami atau produk cacat. Garansi ini berlaku hingga 7 hari setelah produk diterima.'
                    ],
                ]
            ],
        ]);
        $cta = data_get($faqData, 'cta', []);
    @endphp

    <!-- Hero Section — Clean White Style (same as jenis.blade) -->
    <section class="pt-40 pb-20 bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[800px] mx-auto px-6 text-center">
            <span
                class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-semibold tracking-wider uppercase mb-6">
                Bantuan & Dukungan
            </span>
            <h1 class="display-lg text-cb-ink mb-6">Pertanyaan yang Sering Ditanyakan</h1>
            <p class="text-lg text-cb-body leading-relaxed">
                Temukan jawaban cepat untuk pertanyaan umum tentang pemesanan lanyard custom.
                Tidak menemukan jawaban Anda? Tim kami siap membantu.
            </p>
        </div>
    </section>

    <!-- Quick Contact Cards — Simple 3-Color Scheme -->
    <section class="py-16 bg-cb-surface-soft border-b border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-6">
                <!-- WhatsApp: Green accent -->
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '6281234567890') }}"
                    target="_blank" rel="noopener noreferrer"
                    class="group bg-cb-canvas p-6 rounded-cb-xl border border-cb-hairline hover:border-green-500/30 hover:shadow-lg transition-all">
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mb-4">
                        <i data-lucide="message-circle" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-cb-ink mb-1">WhatsApp</h3>
                    <p class="text-sm text-cb-body">Respon tercepat dalam 5 menit</p>
                </a>

                <!-- Email: Blue accent (brand color) -->
                <a href="mailto:{{ $settings->contact_email ?? 'hello@lanyardbagus.com' }}"
                    class="group bg-cb-canvas p-6 rounded-cb-xl border border-cb-hairline hover:border-cb-blue/30 hover:shadow-lg transition-all">
                    <div class="w-12 h-12 rounded-full bg-cb-blue/5 flex items-center justify-center mb-4">
                        <i data-lucide="mail" class="w-6 h-6 text-cb-blue"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-cb-ink mb-1">Email</h3>
                    <p class="text-sm text-cb-body">Balasan dalam 24 jam</p>
                </a>

                <!-- Hours: Neutral gray -->
                <div class="group bg-cb-canvas p-6 rounded-cb-xl border border-cb-hairline">
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <i data-lucide="clock" class="w-6 h-6 text-gray-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-cb-ink mb-1">Jam Operasional</h3>
                    <p class="text-sm text-cb-body">Senin - Sabtu, 08.00 - 18.00 WIB</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Categories & Accordion -->
    <section class="py-section bg-cb-canvas">
        <div class="max-w-[1000px] mx-auto px-6">
            <div class="text-center mb-16">
                <span
                    class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-semibold tracking-wider uppercase mb-4">
                    FAQ
                </span>
                <h2 class="display-md text-cb-ink mb-4">
                    Semua yang Perlu Anda Ketahui
                </h2>
                <p class="text-base text-cb-body max-w-2xl mx-auto">
                    Kami mengumpulkan pertanyaan paling umum untuk membantu Anda memutuskan dengan percaya diri.
                </p>
            </div>

            <div class="space-y-12">
                @foreach($categories as $category)
                    <div class="bg-cb-canvas rounded-cb-xl p-8 border border-cb-hairline">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                <i data-lucide="{{ data_get($category, 'icon', 'help-circle') }}"
                                    class="w-5 h-5 text-gray-600"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-cb-ink">{{ data_get($category, 'title') }}</h3>
                        </div>

                        <div class="space-y-4">
                            @foreach(data_get($category, 'items', []) as $item)
                                <details class="group bg-cb-surface-soft rounded-cb-lg border border-cb-hairline overflow-hidden">
                                    <summary
                                        class="flex items-center justify-between p-5 cursor-pointer list-none hover:bg-cb-hairline/50 transition-colors">
                                        <span class="font-medium text-cb-ink pr-4">{{ data_get($item, 'question') }}</span>
                                        <span class="shrink-0 w-8 h-8 rounded-full bg-cb-canvas flex items-center justify-center">
                                            <i data-lucide="plus"
                                                class="w-4 h-4 text-cb-muted transition-all group-open:rotate-45"></i>
                                        </span>
                                    </summary>
                                    <div class="px-5 pb-5 pt-0">
                                        <div class="pt-4 border-t border-cb-hairline">
                                            <p class="text-cb-body leading-relaxed">
                                                {{ data_get($item, 'answer') }}
                                            </p>
                                        </div>
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Still Have Questions CTA — Clean Light Style -->
    <section class="py-section bg-cb-surface-soft border-t border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-10">
                <div class="max-w-xl">
                    <h2 class="display-sm text-cb-ink mb-4">
                        Masih Punya Pertanyaan?
                    </h2>
                    <p class="text-cb-body text-lg">
                        Tim support kami siap membantu Anda. Hubungi kami sekarang dan dapatkan konsultasi gratis.
                    </p>
                </div>
                <div class="flex flex-wrap gap-4">
                    <a href="/kontak"
                        class="inline-flex items-center gap-2 bg-cb-blue text-white px-8 py-4 rounded-cb-pill font-semibold hover:bg-cb-blue-active transition-colors">
                        <i data-lucide="message-circle" class="w-5 h-5"></i>
                        Hubungi Kami
                    </a>
                    <a href="/blog"
                        class="inline-flex items-center gap-2 bg-cb-canvas text-cb-ink border border-cb-hairline px-8 py-4 rounded-cb-pill font-semibold hover:bg-cb-surface-soft transition-all">
                        <i data-lucide="book-open" class="w-5 h-5"></i>
                        Baca Blog
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Auto-rotate FAQ based on URL hash
        document.addEventListener('DOMContentLoaded', function () {
            const hash = window.location.hash;
            if (hash) {
                const target = document.querySelector(hash);
                if (target && target.tagName === 'DETAILS') {
                    target.open = true;
                    target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    </script>
@endpush