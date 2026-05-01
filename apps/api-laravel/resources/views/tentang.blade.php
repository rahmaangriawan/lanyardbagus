@extends('layouts.main')

@section('title', 'Tentang Kami - ' . ($settings->site_title ?? 'Lanyardbagus'))
@section('meta_description', 'Lanyardbagus adalah produsen lanyard custom premium di Indonesia. Pelajari visi, misi, dan komitmen kami dalam memberikan identitas berkualitas.')

@section('content')
    <!-- Hero Section -->
    <section class="pt-40 pb-20 bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[1000px] mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-semibold tracking-wider uppercase mb-6">
                        Tentang Kami
                    </span>
                    <h1 class="display-lg text-cb-ink mb-6">Setiap Tali Menceritakan Kisah Sebuah Brand</h1>
                    <p class="text-lg text-cb-body leading-relaxed mb-6">
                        Sejak 2018, kami percaya bahwa identitas profesional yang kuat dimulai dari detail terkecil. Lanyardbagus hadir untuk mengubah tali pengenal biasa menjadi simbol kebanggaan bagi ribuan perusahaan di Indonesia.
                    </p>
                    <p class="text-cb-body leading-relaxed">
                        Bukan sekadar aksesoris, tapi representasi dari dedikasi, profesionalisme, dan karakter brand yang Anda bangun.
                    </p>
                </div>
                <div class="relative">
                    <div class="aspect-[4/3] rounded-cb-xl overflow-hidden border border-cb-hairline shadow-xl">
                        <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=800&q=80" 
                             alt="Tim Lanyardbagus" 
                             class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats with Animation -->
    <section class="py-section bg-cb-canvas border-b border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="display-md text-cb-blue mb-2" data-counter="5000" data-suffix="+">0</div>
                    <p class="text-cb-body">Pesanan Selesai</p>
                </div>
                <div class="text-center">
                    <div class="display-md text-cb-blue mb-2" data-counter="7" data-suffix="+">0</div>
                    <p class="text-cb-body">Tahun Berpengalaman</p>
                </div>
                <div class="text-center">
                    <div class="display-md text-cb-blue mb-2" data-counter="50" data-suffix="+">0</div>
                    <p class="text-cb-body">Kota Terlayani</p>
                </div>
                <div class="text-center">
                    <div class="display-md text-cb-blue mb-2" data-counter="100" data-suffix="%">0</div>
                    <p class="text-cb-body">Kepuasan Klien</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi & Misi -->
    <section class="py-section bg-cb-surface-soft border-b border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Visi -->
                <div class="bg-cb-canvas p-10 rounded-cb-xl border border-cb-hairline">
                    <div class="w-14 h-14 rounded-full bg-cb-blue/10 flex items-center justify-center mb-6">
                        <i data-lucide="eye" class="w-7 h-7 text-cb-blue"></i>
                    </div>
                    <h2 class="display-sm text-cb-ink mb-4">Visi</h2>
                    <p class="text-cb-body text-lg leading-relaxed">
                        Menjadi partner branding terdepan yang menginspirasi setiap bisnis di Indonesia untuk menampilkan identitasnya dengan bangga dan profesional.
                    </p>
                </div>

                <!-- Misi -->
                <div class="bg-cb-canvas p-10 rounded-cb-xl border border-cb-hairline">
                    <div class="w-14 h-14 rounded-full bg-cb-blue/10 flex items-center justify-center mb-6">
                        <i data-lucide="target" class="w-7 h-7 text-cb-blue"></i>
                    </div>
                    <h2 class="display-sm text-cb-ink mb-4">Misi</h2>
                    <ul class="space-y-3 text-cb-body">
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cb-blue mt-2 shrink-0"></span>
                            <span>Menghadirkan produk lanyard premium dengan standar kualitas tertinggi</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cb-blue mt-2 shrink-0"></span>
                            <span>Memberikan pengalaman layanan yang personal dan memuaskan</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cb-blue mt-2 shrink-0"></span>
                            <span>Mendukung pertumbuhan brand lokal melalui identitas profesional</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-cb-blue mt-2 shrink-0"></span>
                            <span>Inovasi berkelanjutan dalam desain dan teknologi produksi</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="py-section bg-cb-canvas">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-flex px-4 py-1.5 rounded-cb-pill bg-cb-surface-strong text-cb-blue text-xs font-semibold tracking-wider uppercase mb-4">
                    Nilai-Nilai
                </span>
                <h2 class="display-md text-cb-ink mb-4">Apa yang Kami Junjung Tinggi</h2>
                <p class="text-cb-body max-w-2xl mx-auto">Tiga pilar yang mendasari setiap tali yang kami produksi.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-cb-surface-soft p-8 rounded-cb-xl border border-cb-hairline text-center">
                    <div class="w-14 h-14 rounded-full bg-cb-blue/10 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="gem" class="w-7 h-7 text-cb-blue"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-cb-ink mb-3">Kualitas Premium</h3>
                    <p class="text-cb-body">Kami hanya menggunakan bahan terbaik dan teknik cetak mutakhir untuk memastikan hasil yang tahan lama.</p>
                </div>

                <div class="bg-cb-surface-soft p-8 rounded-cb-xl border border-cb-hairline text-center">
                    <div class="w-14 h-14 rounded-full bg-cb-blue/10 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="clock" class="w-7 h-7 text-cb-blue"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-cb-ink mb-3">Tepat Waktu</h3>
                    <p class="text-cb-body">Kami memahami pentingnya deadline. Sistem produksi kami dirancang untuk mengantarkan pesanan tepat waktu.</p>
                </div>

                <div class="bg-cb-surface-soft p-8 rounded-cb-xl border border-cb-hairline text-center">
                    <div class="w-14 h-14 rounded-full bg-cb-blue/10 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="heart-handshake" class="w-7 h-7 text-cb-blue"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-cb-ink mb-3">Layanan Personal</h3>
                    <p class="text-cb-body">Setiap klien mendapat perhatian penuh dari tim kami. Konsultasi gratis, desain revisi, dan dukungan purna jual.</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Counter Animation
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('[data-counter]');
        
        const animateCounter = (counter) => {
            const target = parseInt(counter.getAttribute('data-counter'));
            const suffix = counter.getAttribute('data-suffix') || '';
            const duration = 2000; // 2 seconds
            const step = target / (duration / 16); // 60fps
            let current = 0;
            
            const updateCounter = () => {
                current += step;
                if (current < target) {
                    counter.textContent = Math.floor(current) + suffix;
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target + suffix;
                }
            };
            
            updateCounter();
        };

        // Intersection Observer for triggering animation when in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => observer.observe(counter));
    });
</script>
@endpush
