SET FOREIGN_KEY_CHECKS = 0;

-- 1. Banners
CREATE TABLE IF NOT EXISTS `banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `badge` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

TRUNCATE TABLE `banners`;
INSERT INTO `banners` (`id`, `title`, `description`, `badge`, `image_url`, `card_image_url`, `link_url`, `button_text`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Selamat datang di Lanyardbagus', 'Tinggal kirim desain atau brief, sisanya kami yang handle. Produksi rapi, kualitas jelas, dan siap kirim ke seluruh Indonesia untuk berbagai kebutuhan.', NULL, 'https://lanyardbagus.com/storage/media/lanyard-hero-dac089.webp', 'https://lanyardbagus.com/storage/media/hero-lanyard-uVHUw.webp', NULL, NULL, 0, 1, '2026-04-29 00:27:26', '2026-04-30 08:44:53');

-- 2. Settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at?` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Fix table structure if needed and TRUNCATE
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `created_at`, `updated_at`) VALUES
(1, 'r2_account_id', '829333b8296764825ba7f711234739e8', 'r2', '2026-04-28 22:04:17', '2026-04-28 22:04:17'),
(2, 'r2_access_key_id', '0a76529654f3c26cac609e9bcab6fd1d', 'general', '2026-04-28 22:04:18', '2026-04-30 09:21:48'),
(3, 'r2_secret_access_key', 'd5b9801c227a20d2fdcf1181d1f14fe69237860a209cd235e6ad6145f88b2ff8', 'general', '2026-04-28 22:04:18', '2026-04-30 09:21:48'),
(4, 'r2_bucket_name', 'lanyardbagus', 'general', '2026-04-28 22:04:18', '2026-04-30 09:21:48'),
(5, 'r2_endpoint', 'https://829333b8296764825ba7f711234739e8.r2.cloudflarestorage.com', 'general', '2026-04-28 22:04:18', '2026-04-30 09:21:48'),
(6, 'r2_public_url', 'https://pub-45cecdcf36b74968982a45979c2b5c4f.r2.dev', 'general', '2026-04-28 22:04:18', '2026-04-30 09:21:48'),
(7, 'google_search_console', NULL, 'verification', '2026-04-30 09:13:28', '2026-04-30 09:13:28'),
(8, 'google_analytics', NULL, 'verification', '2026-04-30 09:13:29', '2026-04-30 09:13:29'),
(9, 'facebook_domain_verification', NULL, 'verification', '2026-04-30 09:13:29', '2026-04-30 09:13:29'),
(10, 'bing_webmaster', NULL, 'verification', '2026-04-30 09:13:29', '2026-04-30 09:13:29');

-- 3. Leads
CREATE TABLE IF NOT EXISTS `leads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `design_url` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

TRUNCATE TABLE `leads`;
INSERT INTO `leads` (`id`, `name`, `whatsapp`, `address`, `size`, `email`, `message`, `design_url`, `created_at`, `updated_at`) VALUES
(8, 'Test User', '081234567890', NULL, NULL, NULL, 'Test upload', 'https://pub-45cecdcf36b74968982a45979c2b5c4f.r2.dev/leads/lead-test-Qp5IX37i.webp', '2026-04-30 09:24:02', '2026-04-30 09:24:02'),
(9, 'Rahma Angriawan', '08967323123', NULL, NULL, 'wantek.id@gmail.com', NULL, 'https://pub-45cecdcf36b74968982a45979c2b5c4f.r2.dev/leads/lanyard-11-XwdTvD4O.webp', '2026-04-30 09:24:40', '2026-04-30 09:24:40');

-- 4. Site Settings (Ensure structure and update)
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `homepage_seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `homepage_meta_description` text COLLATE utf8mb4_unicode_ci,
  `homepage_og_image_id` int unsigned DEFAULT NULL,
  `logo_image_id` int unsigned DEFAULT NULL,
  `favicon_image_id` int unsigned DEFAULT NULL,
  `homepage_data` json DEFAULT NULL,
  `google_site_verification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bing_site_verification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at?` timestamp NULL DEFAULT NULL,
  `updated_at?` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

TRUNCATE TABLE `site_settings`;
INSERT INTO `site_settings` (`id`, `site_title`, `whatsapp_number`, `homepage_seo_title`, `homepage_meta_description`, `homepage_og_image_id`, `logo_image_id`, `favicon_image_id`, `homepage_data`, `created_at`, `updated_at`) VALUES
(1, 'Lanyard Bagus', '089674256043', 'Pabrik Lanyard Custom | Desain Custom Sendiri', 'Kami hadir untuk memenuhi kebutuhan lanyard Anda, dari desain hingga produksi dengan hasil rapi, kuat, dan siap digunakan di berbagai acara.', 1, 1, 2, '{"cta": {"title": "Setiap Tim Hebat Memiliki Cerita.\\nMari Cetak Cerita Anda Bersama Kami.", "description": "Jangan biarkan desain terbaik Anda rusak oleh vendor yang salah. Hubungi kami hari ini untuk konsultasi bahan, desain gratis, dan amankan jadwal produksi Anda.", "primary_href": "whatsapp", "primary_label": "Pesan via WhatsApp", "secondary_href": "#produk", "secondary_label": "Lihat Daftar Harga"}, "faq": {"badge": "Tanya Jawab", "items": [{"answer": "Kami menerima pemesanan dalam jumlah fleksibel. Untuk mendapatkan harga terbaik dan efisiensi produksi, kami merekomendasikan minimum order sebanyak 50 pcs. Namun, jangan ragu untuk berkonsultasi jika kebutuhan Anda di bawah jumlah tersebut.", "question": "Berapa minimum pemesanan lanyard custom?"}, {"answer": "Standar waktu produksi kami berkisar antara 3 hingga 7 hari kerja tergantung dari jumlah pesanan dan antrean produksi. Kami juga menyediakan layanan prioritas (express) untuk kebutuhan mendesak dengan biaya tambahan.", "question": "Berapa lama waktu pengerjaan produksi?"}, {"answer": "Tentu saja! Tim desain internal kami siap membantu memvisualisasikan ide Anda. Layanan desain dasar ini kami berikan secara gratis khusus untuk pelanggan yang telah melakukan pembayaran uang muka pesanan.", "question": "Apakah saya bisa meminta dibuatkan desain?"}, {"answer": "Untuk hasil cetak paling tajam dengan kenyamanan maksimal, kami sangat menyarankan bahan Tissue (Polyester halus) dipadukan dengan teknik Printing Premium. Bahan ini sangat lembut di leher dan mampu menyerap tinta sublimasi dengan sempurna.", "question": "Bahan apa yang paling direkomendasikan?"}], "title": "Pertanyaan Seputar Lanyard"}, "hero": {"badge": "TALI BERKUALITAS PREMIUM", "title": "Selamat Datang di Lanyard Bagus", "image_url": "https://lanyardbagus.com/storage/media/banner-91acfd.webp", "button_url": "#cerita", "card_badge": "Premium Quality", "button_text": "Lihat Cerita Kami", "description": "Bayangkan seluruh tim Anda berjalan dengan penuh kebanggaan, mengenakan lanyard yang mewakili profesionalisme dan visi besar perusahaan.", "card_image_url": "/storage/media/hero-lanyard-5bc7f4.webp", "secondary_button_url": "#hubungi", "secondary_button_text": "Konsultasi Desain"}, "story": {"badge": "Cara Kami Bekerja", "steps": [{"title": "Bahan Pilihan yang Menghargai Kenyamanan Tim Anda", "number": "01", "image_url": "https://lanyardbagus.com/storage/media/bahan-lanyard-pilihan-deFQ6.webp", "description": "Kami memilih serat kain yang halus di kulit dan nyaman dipakai seharian."}, {"title": "Warna Cerah yang Meneriakkan Karakter Brand Anda", "number": "02", "image_url": "https://lanyardbagus.com/storage/media/warna-tajam-UyavC.webp", "description": "Setiap detail warna diterjemahkan dengan presisi mutlak agar identitas Anda tetap kuat."}, {"title": "Tiba Tepat Waktu, Jauh Sebelum Momen Besar Dimulai", "number": "03", "image_url": "https://lanyardbagus.com/storage/media/pengiriman-tepat-waktu-W3msA.webp", "description": "Sistem produksi kami dirancang untuk satu janji: selesai tepat waktu dan tanpa kompromi."}], "title": "Perjalanan Menciptakan Kebanggaan", "description": "Bagi kami, ini bukan tentang seberapa banyak kami mencetak, tapi seberapa sempurna setiap helai tali mewakili nama besar Anda."}, "footer": {"social_links": [], "layanan_links": [{"href": "/tentang", "label": "Tentang Kami"}, {"href": "/kontak", "label": "Kontak Kami"}, {"href": "/pengiriman", "label": "Info Pengiriman"}, {"href": "/kebijakan", "label": "Kebijakan & Syarat"}], "layanan_title": "Profil", "copyright_text": "Dibuat dengan dedikasi untuk branding identitas.", "insights_links": [{"href": "/blog", "label": "Semua Artikel"}, {"href": "/faq", "label": "FAQ"}], "insights_title": "Insights", "whatsapp_label": "Hubungi CS", "navigation_links": [{"href": "/produk", "label": "Produk"}, {"href": "/jenis", "label": "Jenis"}, {"href": "/harga", "label": "Daftar Harga"}], "navigation_title": "Navigasi"}, "contact": {"badge": "Pesan Online", "hints": {"file": "PNG, JPG, WebP \u2014 Maks. 5MB"}, "steps": {"1": "Data Diri", "2": "Kontak", "3": "Detail"}, "title": "Mulai Pesanan Anda", "labels": {"file": "Upload Referensi Desain", "name": "Nama Lengkap", "email": "Email", "address": "Alamat Lengkap", "message": "Pesan", "whatsapp": "No. WhatsApp"}, "buttons": {"back": "Kembali", "file": "Klik atau seret gambar ke sini", "next": "Lanjutkan", "submit": "Kirim Pesanan"}, "description": "Isi formulir di bawah dan tim kami akan segera menghubungi Anda.", "placeholders": {"name": "Masukkan nama lengkap Anda", "email": "email@contoh.com", "address": "Alamat pengiriman Anda...", "message": "Ceritakan kebutuhan lanyard Anda...", "whatsapp": "08xx-xxxx-xxxx"}}, "problem": {"badge": "MENGAPA KAMI HADIR?", "items": [{"icon": "calendar-x", "title": "Acara Sudah Dekat, Tali Belum Jadi", "description": "Ketegangan saat vendor berjanji palsu dan membiarkan panitia cemas di hari-hari krusial."}, {"icon": "droplet", "title": "Warna Pudar yang Merusak Penampilan", "description": "Anda sudah susah payah mendesain logo perusahaan yang indah, namun hasil cetaknya buram dan luntur."}, {"icon": "frown", "title": "Bahan Kaku dan Membuat Gatal", "description": "Memaksa tim Anda memakai lanyard berbahan kasar seharian, membuat mereka tidak nyaman."}], "title": "Membangun Identitas Seharusnya Tidak Membuat Anda Frustrasi", "description": "Kami tahu betapa melelahkannya mencari vendor yang benar-benar peduli pada detail acara Anda."}, "customize": {"badge": "Koleksi Kami", "items": [{"href": "/produk", "icon": "printer", "title": "Printing Premium", "image_url": "https://lanyardbagus.com/storage/media/aset-lanyard-2-S4NP1.webp", "description": "Pilihan terpopuler. Mampu menampilkan gradasi warna dan desain kompleks tanpa batas dengan presisi luar biasa."}, {"href": "/produk#paketidentitas", "icon": "badge-check", "title": "Paket Lanyard + ID Card", "image_url": "https://lanyardbagus.com/storage/media/paket-lanyard-id-card-BoSmK.webp", "description": "Harmonisasi sempurna. Tali dan kartu identitas yang dirancang senada untuk menciptakan kesatuan visual yang solid."}, {"href": "/produk#2sisi", "icon": "layers", "title": "Lanyard 2 Sisi", "image_url": "https://lanyardbagus.com/storage/media/lanyard-2-sisi-iCca6.webp", "description": "Maksimalkan area desain Anda dengan cetakan bolak-balik. Sempurna untuk visibilitas brand ekstra dari segala sudut."}], "title": "Pilih Kanvas untuk Identitas Anda"}, "header_cta": {"href": "/kontak", "label": "Pesan Sekarang"}, "navigation_links": [{"href": "/", "label": "Beranda"}, {"href": "/jenis", "label": "Jenis"}, {"href": "/produk", "label": "Produk"}, {"href": "/harga", "label": "Harga"}, {"href": "/faq", "label": "FAQ"}]}', '2026-04-28 20:16:43', '2026-05-01 09:19:20');

SET FOREIGN_KEY_CHECKS = 1;
