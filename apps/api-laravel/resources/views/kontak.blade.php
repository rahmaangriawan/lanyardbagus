@extends('layouts.main')

@section('title', 'Kontak Kami - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', 'Hubungi tim Lanyardbagus untuk konsultasi gratis, penawaran harga, atau informasi lebih lanjut tentang produk lanyard custom.')

@section('content')
    <!-- Hero Section — Clean White Style -->
    <section class="pt-40 pb-20 bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[800px] mx-auto px-6 text-center">
            <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-semibold tracking-wider uppercase mb-6">
                Hubungi Kami
            </span>
            <h1 class="display-lg text-cb-ink mb-6">Mari Berbicara</h1>
            <p class="text-lg text-cb-body leading-relaxed">
                Tim kami siap membantu mewujudkan identitas brand Anda. 
                Pilih cara yang paling nyaman untuk menghubungi kami.
            </p>
        </div>
    </section>

    <!-- Contact Methods -->
    <section class="py-section bg-cb-surface-soft border-b border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8">
                <!-- WhatsApp -->
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp_number ?? '6281234567890') }}" 
                   target="_blank" rel="noopener noreferrer"
                   class="group bg-cb-canvas p-8 rounded-cb-xl border border-cb-hairline hover:border-green-500/30 hover:shadow-lg transition-all text-center">
                    <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="message-circle" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-cb-ink mb-2">WhatsApp</h3>
                    <p class="text-cb-body mb-4">Respon tercepat dalam 5 menit</p>
                    <span class="text-green-600 font-medium">{{ $settings->whatsapp_number ?? 'Hubungi CS' }}</span>
                </a>

                <!-- Email -->
                <a href="mailto:{{ $settings->contact_email ?? 'hello@lanyardbagus.com' }}"
                   class="group bg-cb-canvas p-8 rounded-cb-xl border border-cb-hairline hover:border-cb-blue/30 hover:shadow-lg transition-all text-center">
                    <div class="w-16 h-16 rounded-full bg-cb-blue/5 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="mail" class="w-8 h-8 text-cb-blue"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-cb-ink mb-2">Email</h3>
                    <p class="text-cb-body mb-4">Balasan dalam 24 jam kerja</p>
                    <span class="text-cb-blue font-medium">{{ $settings->contact_email ?? 'hello@lanyardbagus.com' }}</span>
                </a>

                <!-- Jam Operasional -->
                <div class="group bg-cb-canvas p-8 rounded-cb-xl border border-cb-hairline text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="clock" class="w-8 h-8 text-gray-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-cb-ink mb-2">Jam Operasional</h3>
                    <p class="text-cb-body mb-4">Kami siap melayani Anda</p>
                    <span class="text-gray-600 font-medium">Senin - Sabtu<br>08.00 - 18.00 WIB</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Form -->
    <section class="py-section bg-cb-canvas">
        <div class="max-w-[800px] mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="display-sm text-cb-ink mb-4">Kirim Pesan Cepat</h2>
                <p class="text-cb-body">Isi formulir di bawah ini dan kami akan menghubungi Anda segera.</p>
            </div>

            <form action="/api/leads" method="POST" class="bg-cb-surface-soft p-8 rounded-cb-xl border border-cb-hairline">
                @csrf
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-cb-ink mb-2">Nama Lengkap</label>
                        <input type="text" name="name" required
                               class="w-full px-4 py-3 rounded-cb-lg border border-cb-hairline bg-cb-canvas text-cb-ink focus:outline-none focus:border-cb-blue"
                               placeholder="Nama Anda">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-cb-ink mb-2">WhatsApp</label>
                        <input type="text" name="whatsapp" required
                               class="w-full px-4 py-3 rounded-cb-lg border border-cb-hairline bg-cb-canvas text-cb-ink focus:outline-none focus:border-cb-blue"
                               placeholder="08xx-xxxx-xxxx">
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-cb-ink mb-2">Email</label>
                    <input type="email" name="email"
                           class="w-full px-4 py-3 rounded-cb-lg border border-cb-hairline bg-cb-canvas text-cb-ink focus:outline-none focus:border-cb-blue"
                           placeholder="email@contoh.com">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-cb-ink mb-2">Pesan</label>
                    <textarea name="message" rows="4" required
                              class="w-full px-4 py-3 rounded-cb-lg border border-cb-hairline bg-cb-canvas text-cb-ink focus:outline-none focus:border-cb-blue resize-none"
                              placeholder="Ceritakan kebutuhan lanyard Anda..."></textarea>
                </div>
                <button type="submit" class="w-full bg-cb-blue text-white py-4 rounded-cb-pill font-semibold hover:bg-cb-blue-active transition-colors">
                    Kirim Pesan
                </button>
            </form>
        </div>
    </section>
@endsection
