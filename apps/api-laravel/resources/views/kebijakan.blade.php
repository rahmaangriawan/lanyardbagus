@extends('layouts.main')

@section('title', 'Kebijakan Privasi & Syarat - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', 'Kebijakan privasi, syarat dan ketentuan penggunaan layanan Lanyardbagus.')

@section('content')
    <!-- Hero Section -->
    <section class="pt-40 pb-20 bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[800px] mx-auto px-6 text-center">
            <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-semibold tracking-wider uppercase mb-6">
                Legal
            </span>
            <h1 class="display-lg text-cb-ink mb-6">Kebijakan & Syarat</h1>
            <p class="text-lg text-cb-body leading-relaxed">
                Informasi penting mengenai privasi Anda dan ketentuan penggunaan layanan kami.
            </p>
        </div>
    </section>

    <!-- Policy Content -->
    <section class="py-section bg-cb-canvas">
        <div class="max-w-[800px] mx-auto px-6">
            <div class="prose prose-lg max-w-none">
                <h2 class="text-2xl font-semibold text-cb-ink mb-4">Kebijakan Privasi</h2>
                <p class="text-cb-body mb-6">
                    Lanyardbagus menghargai privasi Anda. Informasi pribadi yang Anda berikan (nama, email, nomor telepon, alamat) hanya digunakan untuk:
                </p>
                <ul class="list-disc list-inside text-cb-body mb-8 space-y-2">
                    <li>Memproses dan mengirim pesanan Anda</li>
                    <li>Menghubungi Anda terkait status pesanan</li>
                    <li>Mengirim informasi promo (dengan persetujuan Anda)</li>
                    <li>Meningkatkan layanan kami</li>
                </ul>
                <p class="text-cb-body mb-8">
                    Kami tidak menjual atau membagikan data pribadi Anda kepada pihak ketiga tanpa izin, kecuali untuk keperluan pengiriman yang membutuhkan data penerima.
                </p>

                <h2 class="text-2xl font-semibold text-cb-ink mb-4 mt-12">Syarat & Ketentuan</h2>
                <p class="text-cb-body mb-6">
                    Dengan menggunakan layanan Lanyardbagus, Anda menyetujui syarat dan ketentuan berikut:
                </p>

                <h3 class="text-xl font-semibold text-cb-ink mb-3 mt-8">Pemesanan & Pembayaran</h3>
                <ul class="list-disc list-inside text-cb-body mb-6 space-y-2">
                    <li>Uang muka minimum 50% diperlukan untuk memulai produksi</li>
                    <li>Desain final harus disetujui sebelum produksi dimulai</li>
                    <li>Perubahan desain setelah produksi dimulai tidak dapat dilakukan</li>
                    <li>Sisa pembayaran dilakukan sebelum pengiriman</li>
                </ul>

                <h3 class="text-xl font-semibold text-cb-ink mb-3 mt-8">Garansi & Pengembalian</h3>
                <ul class="list-disc list-inside text-cb-body mb-6 space-y-2">
                    <li>Garansi 100% penggantian untuk cacat produksi</li>
                    <li>Klaim garansi maksimal 7 hari setelah produk diterima</li>
                    <li>Pengembalian dana untuk pembatalan sebelum produksi dimulai</li>
                    <li>Tidak ada pengembalian untuk pesanan custom yang sudah selesai diproduksi</li>
                </ul>

                <h3 class="text-xl font-semibold text-cb-ink mb-3 mt-8">Hak Cipta</h3>
                <p class="text-cb-body mb-8">
                    Klien bertanggung jawab atas hak cipta desain yang diberikan. Lanyardbagus tidak bertanggung jawab atas pelanggaran hak cipta pihak ketiga. Desain yang dibuat oleh tim kami menjadi hak milik klien setelah pembayaran lunas.
                </p>

                <h3 class="text-xl font-semibold text-cb-ink mb-3 mt-8">Force Majeure</h3>
                <p class="text-cb-body">
                    Kami tidak bertanggung jawab atas keterlambatan yang disebabkan oleh keadaan di luar kendali kami (bencana alam, kecelakaan, pemogokan, kebijakan pemerintah, dll).
                </p>
            </div>

            <div class="mt-16 p-6 bg-cb-surface-soft rounded-cb-xl border border-cb-hairline">
                <p class="text-cb-body text-sm">
                    <strong>Terakhir diperbarui:</strong> Mei 2026<br>
                    Jika Anda memiliki pertanyaan tentang kebijakan ini, silakan <a href="/kontak" class="text-cb-blue hover:underline">hubungi kami</a>.
                </p>
            </div>
        </div>
    </section>
@endsection
