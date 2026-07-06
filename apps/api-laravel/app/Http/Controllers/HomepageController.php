<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomepageController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        if (! Schema::hasTable('site_settings')) {
            return response()->json($this->defaultPayload());
        }

        // Bypass cache for admin
        if ($request->user()?->isAdmin()) {
            return response()->json($this->serialize($this->getSingleton()));
        }

        $data = \Illuminate\Support\Facades\Cache::remember('homepage_data', 86400, function () {
            return $this->serialize($this->getSingleton());
        });

        return response()->json($data);
    }

    public function update(Request $request): JsonResponse
    {
        if (! Schema::hasTable('site_settings')) {
            return response()->json([
                'message' => 'Tabel site_settings belum tersedia. Jalankan migration terlebih dahulu.',
            ], 503);
        }

        $data = $request->validate([
            'homepage_data' => ['required', 'array'],
        ]);

        $settings = $this->getSingleton();
        $settings->homepage_data = $data['homepage_data'];
        $settings->save();

        \Illuminate\Support\Facades\Cache::forget('homepage_data');
        \Illuminate\Support\Facades\Cache::forget('site_settings_global');

        return response()->json($this->serialize($settings));
    }

    private function getSingleton(): SiteSetting
    {
        return SiteSetting::with(['homepageOgImage:id,url,file_name', 'logoImage:id,url,file_name', 'faviconImage:id,url,file_name'])->firstOrCreate(
            ['id' => 1],
            []
        );
    }

    private function serialize(SiteSetting $settings): array
    {
        return [
            'homepage_data' => $settings->homepage_data ?? $this->defaultHomepageData(),
        ];
    }

    private function defaultPayload(): array
    {
        return [
            'homepage_data' => $this->defaultHomepageData(),
        ];
    }

    private function defaultHomepageData(): array
    {
        return [
            'navigation_links' => [
                ['label' => 'Beranda', 'href' => '#beranda'],
                ['label' => 'Jenis', 'href' => '/jenis'],
                ['label' => 'Produk', 'href' => '#produk'],
                ['label' => 'Harga', 'href' => '#harga'],
                ['label' => 'Promo', 'href' => '/promo'],
                ['label' => 'FAQ', 'href' => '#faq'],
            ],
            'header_cta' => [
                'label' => 'Pesan Sekarang',
                'href' => '#hubungi',
            ],
            'hero' => [
                'badge' => 'TALI BERKUALITAS PREMIUM',
                'title' => 'Tali Berkualitas, Wujud Nyata Identitas Tim Anda.',
                'description' => 'Bayangkan seluruh tim Anda berjalan dengan penuh kebanggaan, mengenakan lanyard yang mewakili profesionalisme dan visi besar perusahaan.',
                'button_text' => 'Lihat Cerita Kami',
                'button_url' => '#cerita',
                'secondary_button_text' => 'Konsultasi Desain',
                'secondary_button_url' => '#hubungi',
                'image_url' => '/storage/media/hero-lanyard-5bc7f4.webp',
                'card_image_url' => '/storage/media/hero-lanyard-5bc7f4.webp',
                'card_badge' => 'Premium Quality',
            ],
            'problem' => [
                'badge' => 'MENGAPA KAMI HADIR?',
                'title' => 'Membangun Identitas Seharusnya Tidak Membuat Anda Frustrasi',
                'description' => 'Kami tahu betapa melelahkannya mencari vendor yang benar-benar peduli pada detail acara Anda.',
                'items' => [
                    [
                        'icon' => 'calendar-x',
                        'title' => 'Acara Sudah Dekat, Tali Belum Jadi',
                        'description' => 'Ketegangan saat vendor berjanji palsu dan membiarkan panitia cemas di hari-hari krusial.',
                    ],
                    [
                        'icon' => 'droplet',
                        'title' => 'Warna Pudar yang Merusak Penampilan',
                        'description' => 'Anda sudah susah payah mendesain logo perusahaan yang indah, namun hasil cetaknya buram dan luntur.',
                    ],
                    [
                        'icon' => 'frown',
                        'title' => 'Bahan Kaku dan Membuat Gatal',
                        'description' => 'Memaksa tim Anda memakai lanyard berbahan kasar seharian, membuat mereka tidak nyaman.',
                    ],
                ],
            ],
            'story' => [
                'badge' => 'Cara Kami Bekerja',
                'title' => 'Perjalanan Menciptakan Kebanggaan',
                'description' => 'Bagi kami, ini bukan tentang seberapa banyak kami mencetak, tapi seberapa sempurna setiap helai tali mewakili nama besar Anda.',
                'steps' => [
                    [
                        'number' => '01',
                        'title' => 'Material Pilihan yang Menghargai Kenyamanan Tim Anda',
                        'description' => 'Kami memilih serat kain yang halus di kulit dan nyaman dipakai seharian.',
                        'image_url' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=800&q=80',
                    ],
                    [
                        'number' => '02',
                        'title' => 'Warna Tajam yang Meneriakkan Karakter Brand Anda',
                        'description' => 'Setiap detail warna diterjemahkan dengan presisi mutlak agar identitas Anda tetap kuat.',
                        'image_url' => 'https://images.unsplash.com/photo-1614316315266-9e9ebdb1ce45?auto=format&fit=crop&w=800&q=80',
                    ],
                    [
                        'number' => '03',
                        'title' => 'Tiba Tepat Waktu, Jauh Sebelum Momen Besar Dimulai',
                        'description' => 'Sistem produksi kami dirancang untuk satu janji: selesai tepat waktu dan tanpa kompromi.',
                        'image_url' => 'https://images.unsplash.com/photo-1587293852726-70cdb56c2866?auto=format&fit=crop&w=800&q=80',
                    ],
                ],
            ],
            'contact' => [
                'badge' => 'Pesan Online',
                'title' => 'Mulai Pesanan Anda',
                'description' => 'Isi formulir di bawah dan tim kami akan segera menghubungi Anda.',
                'steps' => [
                    '1' => 'Data Diri',
                    '2' => 'Kontak',
                    '3' => 'Detail',
                ],
                'labels' => [
                    'name' => 'Nama Lengkap',
                    'email' => 'Email',
                    'whatsapp' => 'No. WhatsApp',
                    'address' => 'Alamat Lengkap',
                    'message' => 'Pesan',
                    'file' => 'Upload Referensi Desain',
                ],
                'placeholders' => [
                    'name' => 'Masukkan nama lengkap Anda',
                    'email' => 'email@contoh.com',
                    'whatsapp' => '08xx-xxxx-xxxx',
                    'address' => 'Alamat pengiriman Anda...',
                    'message' => 'Ceritakan kebutuhan lanyard Anda...',
                ],
                'buttons' => [
                    'next' => 'Lanjutkan',
                    'back' => 'Kembali',
                    'submit' => 'Kirim Pesanan',
                    'file' => 'Klik atau seret gambar ke sini',
                ],
                'hints' => [
                    'file' => 'PNG, JPG, WebP — Maks. 5MB',
                ],
            ],
            'footer' => [
                'navigation_title' => 'Navigasi',
                'navigation_links' => [
                    ['label' => 'Beranda', 'href' => '/'],
                    ['label' => 'Tentang Kami', 'href' => '/tentang'],
                    ['label' => 'Jenis Material', 'href' => '/jenis'],
                    ['label' => 'Daftar Harga', 'href' => '/harga'],
                ],
                'insights_title' => 'Insights',
                'insights_links' => [
                    ['label' => 'Semua Artikel', 'href' => '/blog'],
                ],
                'layanan_title' => 'Layanan',
                'layanan_links' => [
                    ['label' => 'Kontak Kami', 'href' => '/kontak'],
                    ['label' => 'Info Pengiriman', 'href' => '/pengiriman'],
                    ['label' => 'Kebijakan & Syarat', 'href' => '/kebijakan'],
                ],
                'social_links' => [
                    ['icon' => 'instagram', 'href' => '#'],
                    ['icon' => 'facebook', 'href' => '#'],
                    ['icon' => 'youtube', 'href' => '#'],
                ],
                'whatsapp_label' => 'Hubungi CS',
                'copyright_text' => 'Dibuat dengan dedikasi untuk branding identitas.',
            ],
        ];
    }
}
