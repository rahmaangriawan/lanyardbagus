@extends('layouts.main')

@section('title', 'Informasi Pengiriman - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', 'Informasi lengkap tentang waktu pengiriman, jasa kurir, area cakupan, dan tracking pesanan lanyard custom Anda.')

@section('content')
    <!-- Hero Section -->
    <section class="pt-40 pb-20 bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[800px] mx-auto px-6 text-center">
            <span
                class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-semibold tracking-wider uppercase mb-6">
                Pengiriman
            </span>
            <h1 class="display-lg text-cb-ink mb-6">Pesanan Diantar ke Seluruh Indonesia</h1>
            <p class="text-lg text-cb-body leading-relaxed">
                Kami bekerja sama dengan jasa kurir terpercaya untuk memastikan lanyard custom Anda tiba dengan aman dan
                tepat waktu.
            </p>
        </div>
    </section>

    <!-- Shipping Partners -->
    <section class="py-16 bg-cb-surface-soft border-b border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-xl font-semibold text-cb-ink mb-2">Mitra Kurir Kami</h2>
                <p class="text-cb-body">Pengiriman aman dengan jasa kurir terpercaya</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-cb-canvas p-6 rounded-cb-lg border border-cb-hairline text-center">
                    <div class="h-16 flex items-center justify-center mb-3">
                        <img src="/storage/media/new-logo-jne-66WqN.webp" alt="JNE" class="max-h-full max-w-full object-contain">
                    </div>
                </div>
                <div class="bg-cb-canvas p-6 rounded-cb-lg border border-cb-hairline text-center">
                    <div class="h-16 flex items-center justify-center mb-3">
                        <img src="/storage/media/jt-express-logosvg-GJjXT.webp" alt="J&T Express" class="max-h-full max-w-full object-contain">
                    </div>
                </div>
                <div class="bg-cb-canvas p-6 rounded-cb-lg border border-cb-hairline text-center">
                    <div class="h-16 flex items-center justify-center mb-3">
                        <img src="/storage/media/logo-sicepat-iABco.webp" alt="SiCepat" class="max-h-full max-w-full object-contain">
                    </div>
                </div>
                <div class="bg-cb-canvas p-6 rounded-cb-lg border border-cb-hairline text-center">
                    <div class="h-16 flex items-center justify-center mb-3">
                        <img src="/storage/media/indah-cargo-rQJ7h.webp" alt="Indah Cargo" class="max-h-full max-w-full object-contain">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shipping Time -->
    <section class="py-section bg-cb-canvas">
        <div class="max-w-[1000px] mx-auto px-6">
            <h2 class="display-sm text-cb-ink text-center mb-12">Estimasi Waktu Pengiriman</h2>

            <div class="space-y-6">
                <div
                    class="bg-cb-surface-soft p-6 rounded-cb-xl border border-cb-hairline flex flex-col md:flex-row items-start md:items-center gap-6">
                    <div class="w-14 h-14 bg-cb-blue/10 rounded-full flex items-center justify-center shrink-0">
                        <i data-lucide="map-pin" class="w-7 h-7 text-cb-blue"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-cb-ink mb-1">Jabodetabek</h3>
                        <p class="text-cb-body">Jakarta, Bogor, Depok, Tangerang, Bekasi dan sekitarnya</p>
                    </div>
                    <span
                        class="px-4 py-2 bg-cb-canvas border border-cb-hairline rounded-cb-pill font-semibold text-cb-ink">1-2
                        Hari</span>
                </div>

                <div
                    class="bg-cb-surface-soft p-6 rounded-cb-xl border border-cb-hairline flex flex-col md:flex-row items-start md:items-center gap-6">
                    <div class="w-14 h-14 bg-cb-blue/10 rounded-full flex items-center justify-center shrink-0">
                        <i data-lucide="map" class="w-7 h-7 text-cb-blue"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-cb-ink mb-1">Pulau Jawa</h3>
                        <p class="text-cb-body">Bandung, Semarang, Yogyakarta, Surabaya, Malang dan seluruh kota di Jawa</p>
                    </div>
                    <span
                        class="px-4 py-2 bg-cb-canvas border border-cb-hairline rounded-cb-pill font-semibold text-cb-ink">2-4
                        Hari</span>
                </div>

                <div
                    class="bg-cb-surface-soft p-6 rounded-cb-xl border border-cb-hairline flex flex-col md:flex-row items-start md:items-center gap-6">
                    <div class="w-14 h-14 bg-cb-blue/10 rounded-full flex items-center justify-center shrink-0">
                        <i data-lucide="globe" class="w-7 h-7 text-cb-blue"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-cb-ink mb-1">Luar Jawa</h3>
                        <p class="text-cb-body">Sumatera, Kalimantan, Sulawesi, Bali, NTB, NTT, Papua dan seluruh Indonesia
                        </p>
                    </div>
                    <span
                        class="px-4 py-2 bg-cb-canvas border border-cb-hairline rounded-cb-pill font-semibold text-cb-ink">4-7
                        Hari</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Shipping Info -->
    <section class="py-section bg-cb-surface-soft border-t border-cb-hairline">
        <div class="max-w-[1000px] mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-cb-canvas p-8 rounded-cb-xl border border-cb-hairline">
                    <div class="w-12 h-12 bg-cb-blue/10 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="shield-check" class="w-6 h-6 text-cb-blue"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-cb-ink mb-3">Garansi Pengiriman</h3>
                    <p class="text-cb-body leading-relaxed">Setiap pengiriman dilengkapi asuransi dan nomor resi untuk
                        tracking. Jika ada kerusakan selama pengiriman, kami akan mengganti produk Anda.</p>
                </div>

                <div class="bg-cb-canvas p-8 rounded-cb-xl border border-cb-hairline">
                    <div class="w-12 h-12 bg-cb-blue/10 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="box" class="w-6 h-6 text-cb-blue"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-cb-ink mb-3">Packing Aman</h3>
                    <p class="text-cb-body leading-relaxed">Lanyard Anda dipacking dengan bubble wrap dan box kardus yang
                        kuat untuk melindungi dari benturan dan air selama perjalanan.</p>
                </div>
            </div>
        </div>
    </section>
@endsection