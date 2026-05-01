<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanyardbagus - Ciptakan Kebanggaan Tim Anda</title>
    
    <!-- Google Fonts: Poppins for a Premium Look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Tailwind Config for Custom Colors -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Poppins"', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS for subtle animations and hiding scrollbars -->
    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        /* Memastikan sticky bisa bekerja dengan baik */
        html { scroll-behavior: smooth; }
        body { overflow-x: clip; } /* Mengganti overflow-x-hidden menjadi clip agar sticky tidak rusak */
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <!-- Hero Section: Emosional & Menarik -->
    <section id="beranda" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-white">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-slate-50 z-0"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <!-- Left Content -->
                <div class="flex-1 flex flex-col items-start gap-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-[10px] bg-primary-50 text-primary-700 text-xs font-semibold tracking-wide border border-primary-100">
                        <span class="relative flex h-2 w-2">
                          <span class="absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75 animate-ping"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                        </span>
                        TALI BERKUALITAS PREMIUM
                    </div>
                    <h1 class="text-4xl lg:text-6xl font-extrabold leading-[1.15] text-slate-900">
                        Tali Berkualitas, Wujud Nyata <span class="text-primary-600">Identitas Tim Anda.</span>
                    </h1>
                    <p class="text-lg text-slate-600 leading-relaxed max-w-xl">
                        Bayangkan seluruh tim Anda berjalan dengan penuh kebanggaan, mengenakan lanyard yang mewakili profesionalisme dan visi besar perusahaan. Lanyardbagus merancang setiap detail jahitan untuk menceritakan kisah brand Anda.
                    </p>
                    
                    <div class="flex flex-wrap items-center gap-4 pt-4">
                        <a href="#cerita" class="flex items-center justify-center bg-primary-600 text-white px-8 py-3.5 rounded-[10px] font-medium hover:bg-primary-700 transition-all duration-300 shadow-lg shadow-primary-500/20">
                            Lihat Cerita Kami
                        </a>
                        <a href="#kontak" class="flex items-center justify-center bg-white text-slate-700 border border-slate-200 px-8 py-3.5 rounded-[10px] font-medium hover:border-primary-200 hover:bg-primary-50 hover:text-primary-700 transition-all duration-300">
                            Konsultasi Desain
                        </a>
                    </div>
                </div>

                <!-- Right Image -->
                <div class="flex-1 relative w-full flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-lg aspect-[4/3] lg:aspect-square rounded-[10px] overflow-hidden bg-slate-100 group border border-slate-200">
                        <img src="http://localhost:3000/uploads/media/hero-lanyard.webp" alt="Kebanggaan Memakai Lanyard" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        
                        <div class="absolute bottom-6 left-6 right-6 bg-white p-5 rounded-[10px] shadow-sm border border-slate-100 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-[10px] bg-primary-50 flex items-center justify-center text-primary-600 shrink-0">
                                <i data-lucide="heart-handshake" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">Kepercayaan Ekstra</p>
                                <p class="text-xs text-slate-500 mt-1">Desain premium yang meningkatkan rasa percaya diri pemakainya.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem Section: Storytelling Empathy -->
    <section class="py-24 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16 flex flex-col items-center max-w-2xl mx-auto">
                <span class="text-sm font-bold text-slate-500 tracking-wider uppercase mb-3">MENGAPA KAMI HADIR?</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-4">Membangun Identitas Seharusnya Tidak Membuat Anda Frustrasi</h2>
                <p class="text-slate-600">Kami tahu betapa melelahkannya mencari vendor yang benar-benar peduli pada detail acara Anda. Pernahkah Anda merasakan kekhawatiran ini?</p>
            </div>

            <!-- Card Horizontal dengan Copywriting Empati -->
            <div class="flex flex-wrap justify-center gap-6">
                <div class="flex-[1_1_350px] w-full max-w-[500px] bg-slate-50 p-6 rounded-[10px] border border-slate-200 flex items-start gap-5 transition-all hover:border-slate-300">
                    <div class="w-12 h-12 rounded-[10px] bg-white text-slate-700 flex items-center justify-center shrink-0 border border-slate-200">
                        <i data-lucide="calendar-x" class="w-5 h-5 text-red-500"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Acara Sudah Dekat, Tali Belum Jadi</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">Ketegangan saat vendor berjanji palsu, membiarkan panitia cemas di hari-hari krusial menjelang acara besar Anda.</p>
                    </div>
                </div>

                <div class="flex-[1_1_350px] w-full max-w-[500px] bg-slate-50 p-6 rounded-[10px] border border-slate-200 flex items-start gap-5 transition-all hover:border-slate-300">
                    <div class="w-12 h-12 rounded-[10px] bg-white text-slate-700 flex items-center justify-center shrink-0 border border-slate-200">
                        <i data-lucide="droplet" class="w-5 h-5 text-orange-500"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Warna Pudar yang Merusak Penampilan</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">Anda sudah susah payah mendesain logo perusahaan yang indah, namun hasil cetaknya buram dan luntur dalam hitungan minggu.</p>
                    </div>
                </div>

                <div class="flex-[1_1_350px] w-full max-w-[500px] bg-slate-50 p-6 rounded-[10px] border border-slate-200 flex items-start gap-5 transition-all hover:border-slate-300">
                    <div class="w-12 h-12 rounded-[10px] bg-white text-slate-700 flex items-center justify-center shrink-0 border border-slate-200">
                        <i data-lucide="frown" class="w-5 h-5 text-slate-500"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Bahan Kaku dan Membuat Gatal</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">Memaksa tim Anda memakai lanyard berbahan kasar seharian, membuat mereka tidak nyaman saat melayani klien.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Zigzag Sticky Cards Section: The Story of Our Process -->
    <section id="cerita" class="py-24 bg-slate-100 relative">
        <div class="max-w-5xl mx-auto px-6 relative">
            <div class="text-center mb-20 max-w-2xl mx-auto">
                <span class="text-sm font-bold text-primary-600 tracking-wider uppercase mb-3">Cara Kami Bekerja</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-4">Perjalanan Menciptakan Kebanggaan</h2>
                <p class="text-slate-600">Bagi kami, ini bukan tentang seberapa banyak kami mencetak, tapi seberapa sempurna setiap helai tali itu mewakili nama besar Anda. Inilah janji kami.</p>
            </div>

            <!-- Stacking Sticky Container -->
            <div class="flex flex-col gap-12 pb-32">
                
                <!-- Sticky Card 1 -->
                <div class="sticky top-24 z-10 flex flex-col md:flex-row items-center gap-10 bg-white p-8 border border-slate-200 rounded-[10px] shadow-sm transition-transform duration-500">
                    <div class="w-full md:w-1/2 aspect-video md:aspect-[4/3] relative rounded-[10px] overflow-hidden bg-slate-100 border border-slate-200">
                        <img src="https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=800&q=80" alt="Material Lembut" class="object-cover w-full h-full">
                    </div>
                    <div class="w-full md:w-1/2 flex flex-col items-start text-left">
                        <div class="inline-flex items-center justify-center px-4 py-2 bg-slate-50 border border-slate-200 rounded-[10px] text-sm font-bold text-slate-700 mb-6">
                            01. Sentuhan Pertama
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Material Pilihan yang Menghargai Kenyamanan Tim Anda</h3>
                        <p class="text-slate-600 leading-relaxed mb-6">
                            Kami percaya identitas yang baik harus terasa nyaman saat dikenakan. Lanyardbagus secara eksklusif memilih serat kain bertekstur halus yang ramah di kulit dan menyerap keringat. Tak ada lagi keluhan leher gatal saat meeting panjang atau melayani customer seharian.
                        </p>
                    </div>
                </div>

                <!-- Sticky Card 2 (Zigzag - Reverse) -->
                <div class="sticky top-28 z-20 flex flex-col md:flex-row-reverse items-center gap-10 bg-slate-50 p-8 border border-slate-200 rounded-[10px] shadow-md transition-transform duration-500">
                    <div class="w-full md:w-1/2 aspect-video md:aspect-[4/3] relative rounded-[10px] overflow-hidden bg-slate-200 border border-slate-300">
                        <img src="https://images.unsplash.com/photo-1614316315266-9e9ebdb1ce45?auto=format&fit=crop&w=800&q=80" alt="Cetak Resolusi Tinggi" class="object-cover w-full h-full">
                    </div>
                    <div class="w-full md:w-1/2 flex flex-col items-start text-left">
                        <div class="inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-200 rounded-[10px] text-sm font-bold text-slate-700 mb-6">
                            02. Visual yang Berbicara
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Warna Tajam yang Meneriakkan Karakter Brand Anda</h3>
                        <p class="text-slate-600 leading-relaxed mb-6">
                            Logo Anda menceritakan sejarah; warnanya membangkitkan emosi. Dengan teknologi cetak sublimasi resolusi tinggi kami, setiap detail kode warna (pantone) perusahaan Anda akan diterjemahkan dengan presisi mutlak. Warnanya terkunci kuat di serat kain, melawan pudar bertahun-tahun lamanya.
                        </p>
                    </div>
                </div>

                <!-- Sticky Card 3 -->
                <div class="sticky top-32 z-30 flex flex-col md:flex-row items-center gap-10 bg-slate-900 text-white p-8 border border-slate-800 rounded-[10px] shadow-xl transition-transform duration-500">
                    <div class="w-full md:w-1/2 aspect-video md:aspect-[4/3] relative rounded-[10px] overflow-hidden bg-slate-800 border border-slate-700">
                        <img src="https://images.unsplash.com/photo-1587293852726-70cdb56c2866?auto=format&fit=crop&w=800&q=80" alt="Pengiriman Tepat Waktu" class="object-cover w-full h-full opacity-90">
                    </div>
                    <div class="w-full md:w-1/2 flex flex-col items-start text-left">
                        <div class="inline-flex items-center justify-center px-4 py-2 bg-slate-800 border border-slate-700 rounded-[10px] text-sm font-bold text-slate-300 mb-6">
                            03. Eksekusi Tanpa Kompromi
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Tiba Tepat Waktu, Jauh Sebelum Momen Besar Anda Dimulai</h3>
                        <p class="text-slate-300 leading-relaxed mb-6">
                            Kami mengambil alih kecemasan Anda. Sistem produksi efisien kami dirancang untuk satu janji: produk selesai dan diantarkan tepat waktu. Anda bisa fokus memikirkan kesuksesan acara Anda, biarkan urusan kelengkapan tim menjadi tanggung jawab dan dedikasi kami.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Product Section -->
    <section id="produk" class="py-24 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16 flex flex-col items-center">
                <span class="text-sm font-bold text-primary-600 tracking-wider uppercase mb-3">Koleksi Kami</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900">Pilih Kanvas untuk Identitas Anda</h2>
            </div>

            <div class="flex flex-wrap justify-center gap-6 pb-8">
                <!-- Product 1 -->
                <div class="flex-[1_1_300px] max-w-[380px] flex flex-col group cursor-pointer bg-white border border-slate-200 rounded-[10px] p-4 transition-all hover:border-primary-500 hover:shadow-lg hover:-translate-y-1">
                    <div class="relative w-full aspect-[4/3] rounded-[6px] overflow-hidden mb-5 bg-slate-100 border border-slate-100">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i data-lucide="printer" class="w-10 h-10 text-slate-300"></i>
                        </div>
                        <img src="https://images.unsplash.com/photo-1628126235206-5260b9ea6441?auto=format&fit=crop&w=600&q=80" alt="Lanyard Printing Premium" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-primary-600 transition-colors">Printing Premium</h3>
                        <i data-lucide="arrow-up-right" class="w-5 h-5 text-slate-400 group-hover:text-primary-600 transition-colors"></i>
                    </div>
                    <p class="text-sm text-slate-500 text-left">Pilihan terpopuler. Mampu menampilkan gradasi warna dan desain kompleks tanpa batas dengan presisi luar biasa.</p>
                </div>

                <!-- Product 2 -->
                <div class="flex-[1_1_300px] max-w-[380px] flex flex-col group cursor-pointer bg-white border border-slate-200 rounded-[10px] p-4 transition-all hover:border-primary-500 hover:shadow-lg hover:-translate-y-1">
                    <div class="relative w-full aspect-[4/3] rounded-[6px] overflow-hidden mb-5 bg-slate-100 border border-slate-100">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i data-lucide="badge-check" class="w-10 h-10 text-slate-300"></i>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-primary-600 transition-colors">Paket Identitas (Lanyard + ID Card)</h3>
                        <i data-lucide="arrow-up-right" class="w-5 h-5 text-slate-400 group-hover:text-primary-600 transition-colors"></i>
                    </div>
                    <p class="text-sm text-slate-500 text-left">Harmonisasi sempurna. Tali dan kartu identitas yang dirancang senada untuk menciptakan kesatuan visual yang solid.</p>
                </div>

                <!-- Product 3 -->
                <div class="flex-[1_1_300px] max-w-[380px] flex flex-col group cursor-pointer bg-white border border-slate-200 rounded-[10px] p-4 transition-all hover:border-primary-500 hover:shadow-lg hover:-translate-y-1">
                    <div class="relative w-full aspect-[4/3] rounded-[6px] overflow-hidden mb-5 bg-slate-100 border border-slate-100">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i data-lucide="layers" class="w-10 h-10 text-slate-300"></i>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-primary-600 transition-colors">Lanyard 2 Sisi</h3>
                        <i data-lucide="arrow-up-right" class="w-5 h-5 text-slate-400 group-hover:text-primary-600 transition-colors"></i>
                    </div>
                    <p class="text-sm text-slate-500 text-left">Maksimalkan area desain Anda dengan cetakan bolak-balik. Sempurna untuk visibilitas brand ekstra dari segala sudut.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section (Table Format with partial blur) -->
    <section id="harga" class="py-24 bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-sm font-bold text-primary-600 tracking-wider uppercase mb-3">Transparan & Terjangkau</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900">Estimasi Daftar Harga</h2>
            </div>

            <!-- Table Container with Relative Positioning for the Overlay -->
            <div class="relative bg-white border border-slate-200 rounded-[10px] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="py-4 px-6 font-bold text-slate-900 w-1/3">Produk</th>
                                <th class="py-4 px-6 font-bold text-slate-900 w-1/4">Kuantitas</th>
                                <th class="py-4 px-6 font-bold text-slate-900 w-1/4">Harga / pcs</th>
                                <th class="py-4 px-6 font-bold text-slate-900">Estimasi Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-600">
                            <!-- Row 1 -->
                            <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">Printing Premium (2cm)</div>
                                    <div class="text-xs text-slate-500 mt-1">Bahan Tissue, Stopper, Kait Besi</div>
                                </td>
                                <td class="py-4 px-6">50 - 100 pcs</td>
                                <td class="py-4 px-6 font-semibold">Rp 15.000</td>
                                <td class="py-4 px-6">3-5 Hari Kerja</td>
                            </tr>
                            <!-- Row 2 -->
                            <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">Printing Premium (2cm)</div>
                                    <div class="text-xs text-slate-500 mt-1">Bahan Tissue, Stopper, Kait Besi</div>
                                </td>
                                <td class="py-4 px-6">101 - 500 pcs</td>
                                <td class="py-4 px-6 font-semibold text-primary-600">Rp 12.500</td>
                                <td class="py-4 px-6">4-7 Hari Kerja</td>
                            </tr>
                            <!-- Row 3 -->
                            <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">Paket Identitas Lengkap</div>
                                    <div class="text-xs text-slate-500 mt-1">Lanyard + Holder PVC + ID Card</div>
                                </td>
                                <td class="py-4 px-6">50 - 100 pcs</td>
                                <td class="py-4 px-6 font-semibold">Rp 22.000</td>
                                <td class="py-4 px-6">3-5 Hari Kerja</td>
                            </tr>
                            <!-- Row 4 -->
                            <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">Lanyard 2 Sisi Bolak Balik</div>
                                    <div class="text-xs text-slate-500 mt-1">Bahan Tissue 2.5cm Premium</div>
                                </td>
                                <td class="py-4 px-6">Min 100 pcs</td>
                                <td class="py-4 px-6 font-semibold">Rp 18.000</td>
                                <td class="py-4 px-6">5-7 Hari Kerja</td>
                            </tr>
                            <!-- Row 5 (Partially Hidden) -->
                            <tr class="border-b border-slate-100 opacity-60">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">Lanyard Grosir / Partai Besar</div>
                                    <div class="text-xs text-slate-500 mt-1">Tersedia harga khusus event</div>
                                </td>
                                <td class="py-4 px-6">> 1000 pcs</td>
                                <td class="py-4 px-6 font-semibold">Hubungi Kami</td>
                                <td class="py-4 px-6">Sesuai Kesepakatan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- The overlay element creating the fade-out effect at the bottom -->
                <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-white via-white/80 to-transparent flex items-end justify-center pb-6">
                    <a href="#kontak" class="inline-flex items-center gap-2 bg-white text-primary-600 border border-slate-200 px-6 py-2.5 rounded-[10px] font-bold text-sm hover:border-primary-600 transition-all shadow-sm">
                        Unduh Price List Lengkap <i data-lucide="download" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-24 bg-slate-50 border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-sm font-bold text-primary-600 tracking-wider uppercase mb-3">Tanya Jawab</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900">Pertanyaan Seputar Lanyard</h2>
            </div>
            
            <div class="space-y-4">
                <details class="group bg-white border border-slate-200 rounded-[10px] [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between p-6 cursor-pointer font-bold text-slate-900">
                        <span>Berapa minimum pemesanan lanyard custom?</span>
                        <span class="transition group-open:rotate-180">
                            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500"></i>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-slate-600 text-sm leading-relaxed">
                        Kami menerima pemesanan dalam jumlah fleksibel. Untuk mendapatkan harga terbaik dan efisiensi produksi, kami merekomendasikan minimum order sebanyak 50 pcs. Namun, jangan ragu untuk berkonsultasi jika kebutuhan Anda di bawah jumlah tersebut.
                    </div>
                </details>

                <details class="group bg-white border border-slate-200 rounded-[10px] [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between p-6 cursor-pointer font-bold text-slate-900">
                        <span>Berapa lama waktu pengerjaan produksi?</span>
                        <span class="transition group-open:rotate-180">
                            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500"></i>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-slate-600 text-sm leading-relaxed">
                        Standar waktu produksi kami berkisar antara 3 hingga 7 hari kerja tergantung dari jumlah pesanan dan antrean produksi. Kami juga menyediakan layanan prioritas (express) untuk kebutuhan mendesak dengan biaya tambahan.
                    </div>
                </details>

                <details class="group bg-white border border-slate-200 rounded-[10px] [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between p-6 cursor-pointer font-bold text-slate-900">
                        <span>Apakah saya bisa meminta dibuatkan desain?</span>
                        <span class="transition group-open:rotate-180">
                            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500"></i>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-slate-600 text-sm leading-relaxed">
                        Tentu saja! Tim desain internal kami siap membantu memvisualisasikan ide Anda. Layanan desain dasar ini kami berikan secara gratis khusus untuk pelanggan yang telah melakukan pembayaran uang muka pesanan.
                    </div>
                </details>

                <details class="group bg-white border border-slate-200 rounded-[10px] [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between p-6 cursor-pointer font-bold text-slate-900">
                        <span>Bahan apa yang paling direkomendasikan?</span>
                        <span class="transition group-open:rotate-180">
                            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500"></i>
                        </span>
                    </summary>
                    <div class="px-6 pb-6 text-slate-600 text-sm leading-relaxed">
                        Untuk hasil cetak paling tajam dengan kenyamanan maksimal, kami sangat menyarankan bahan Tissue (Polyester halus) dipadukan dengan teknik Printing Premium. Bahan ini sangat lembut di leher dan mampu menyerap tinta sublimasi dengan sempurna.
                    </div>
                </details>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section id="blog" class="py-24 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <span class="text-sm font-bold text-primary-600 tracking-wider uppercase mb-3 block">Artikel Terbaru</span>
                    <h2 class="text-3xl lg:text-4xl font-bold text-slate-900">Wawasan & Tips</h2>
                </div>
                <a href="#" class="hidden md:inline-flex items-center gap-2 text-primary-600 font-bold hover:text-primary-700 transition">
                    Lihat Semua <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Blog Card 1 -->
                <article class="bg-slate-50 rounded-[10px] border border-slate-200 overflow-hidden group hover:shadow-md transition-all">
                    <div class="aspect-video overflow-hidden bg-slate-200 relative">
                        <img src="https://images.unsplash.com/photo-1558655146-d09347e92766?auto=format&fit=crop&w=600&q=80" alt="Blog Image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 text-xs text-slate-500 mb-3">
                            <span class="font-bold text-primary-600">Panduan</span>
                            <span>•</span>
                            <span>12 Mei 2026</span>
                        </div>
                        <h3 class="font-bold text-lg text-slate-900 mb-2 leading-tight group-hover:text-primary-600 transition-colors">Cara Memilih Bahan Lanyard yang Tepat untuk Karyawan Kantoran</h3>
                        <p class="text-sm text-slate-600 line-clamp-2 mb-4">Kenyamanan adalah kunci. Pelajari perbedaan bahan polyester, nylon, dan tissue agar tim Anda nyaman memakainya seharian.</p>
                        <a href="#" class="inline-flex items-center gap-1 text-sm font-bold text-slate-900 group-hover:text-primary-600 transition">
                            Baca Artikel <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </article>

                <!-- Blog Card 2 -->
                <article class="bg-slate-50 rounded-[10px] border border-slate-200 overflow-hidden group hover:shadow-md transition-all">
                    <div class="aspect-video overflow-hidden bg-slate-200 relative">
                        <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=600&q=80" alt="Blog Image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 text-xs text-slate-500 mb-3">
                            <span class="font-bold text-primary-600">Event</span>
                            <span>•</span>
                            <span>05 Mei 2026</span>
                        </div>
                        <h3 class="font-bold text-lg text-slate-900 mb-2 leading-tight group-hover:text-primary-600 transition-colors">Pentingnya Desain ID Card Seragam untuk Kesuksesan Pameran</h3>
                        <p class="text-sm text-slate-600 line-clamp-2 mb-4">Mengapa keseragaman visual pada identitas panitia sangat mempengaruhi tingkat kepercayaan pengunjung pameran Anda.</p>
                        <a href="#" class="inline-flex items-center gap-1 text-sm font-bold text-slate-900 group-hover:text-primary-600 transition">
                            Baca Artikel <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </article>

                <!-- Blog Card 3 -->
                <article class="bg-slate-50 rounded-[10px] border border-slate-200 overflow-hidden group hover:shadow-md transition-all">
                    <div class="aspect-video overflow-hidden bg-slate-200 relative">
                        <img src="https://images.unsplash.com/photo-1628126235206-5260b9ea6441?auto=format&fit=crop&w=600&q=80" alt="Blog Image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 text-xs text-slate-500 mb-3">
                            <span class="font-bold text-primary-600">Tips Desain</span>
                            <span>•</span>
                            <span>28 Apr 2026</span>
                        </div>
                        <h3 class="font-bold text-lg text-slate-900 mb-2 leading-tight group-hover:text-primary-600 transition-colors">5 Kesalahan Umum Saat Mendesain Lanyard Custom Sendiri</h3>
                        <p class="text-sm text-slate-600 line-clamp-2 mb-4">Hindari font yang terlalu kecil atau komposisi warna yang bertabrakan. Ikuti tips praktis ini sebelum mencetak massal.</p>
                        <a href="#" class="inline-flex items-center gap-1 text-sm font-bold text-slate-900 group-hover:text-primary-600 transition">
                            Baca Artikel <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </article>
            </div>
            
            <div class="mt-8 text-center md:hidden">
                <a href="#" class="inline-flex items-center justify-center border border-slate-300 text-slate-700 px-6 py-3 rounded-[10px] font-medium hover:border-primary-600 hover:text-primary-600 transition-all">
                    Lihat Semua Artikel
                </a>
            </div>
        </div>
    </section>

    <!-- Call to Action Footer -->
    <section id="kontak" class="py-16 bg-slate-50">
        <div class="max-w-5xl mx-auto px-6">
            <div class="relative rounded-[10px] overflow-hidden bg-slate-900 text-white border border-slate-800 shadow-xl">
                <!-- Aksen halus di background CTA -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-slate-800 rounded-full blur-3xl opacity-50 -translate-y-1/2 translate-x-1/2"></div>
                
                <div class="relative z-10 p-12 lg:p-20 flex flex-col lg:flex-row items-center justify-between gap-10">
                    <div class="text-center lg:text-left flex-1">
                        <h2 class="text-3xl lg:text-4xl font-bold mb-4">Setiap Tim Hebat Memiliki Cerita.<br/>Mari Cetak Cerita Anda Bersama Kami.</h2>
                        <p class="text-slate-400 text-lg max-w-xl mb-8">
                            Jangan biarkan desain terbaik Anda rusak oleh vendor yang salah. Hubungi kami hari ini untuk konsultasi bahan, desain gratis, dan amankan jadwal produksi Anda.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                            <a href="https://wa.me/628000000000" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-primary-600 text-white px-8 py-4 rounded-[10px] font-bold hover:bg-primary-500 transition-colors duration-300">
                                <i data-lucide="message-square" class="w-5 h-5"></i>
                                Pesan via WhatsApp
                            </a>
                            <a href="#produk" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-800 text-white border border-slate-700 px-8 py-4 rounded-[10px] font-bold hover:bg-slate-700 transition-colors duration-300">
                                Lihat Daftar Harga
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Initialize Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>