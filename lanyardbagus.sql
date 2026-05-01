SET FOREIGN_KEY_CHECKS = 0;
-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table lanyardbagus.banners
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.banners: ~1 rows (approximately)
INSERT INTO `banners` (`id`, `title`, `description`, `badge`, `image_url`, `card_image_url`, `link_url`, `button_text`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Selamat datang di Lanyardbagus', 'Tinggal kirim desain atau brief, sisanya kami yang handle. Produksi rapi, kualitas jelas, dan siap kirim ke seluruh Indonesia untuk berbagai kebutuhan.', NULL, 'http://localhost:8000/storage/media/lanyard-hero-dac089.webp', 'http://localhost:8000/storage/media/hero-lanyard-uVHUw.webp', NULL, NULL, 0, 1, '2026-04-29 00:27:26', '2026-04-30 08:44:53');

-- Dumping structure for table lanyardbagus.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.cache: ~0 rows (approximately)

-- Dumping structure for table lanyardbagus.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.cache_locks: ~0 rows (approximately)

-- Dumping structure for table lanyardbagus.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.categories: ~1 rows (approximately)
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `seo_title`, `meta_description`, `created_at`, `updated_at`) VALUES
	(1, 'Pengetahuan', 'pengetahuan', 'Adalah kategori pengetahuan dan wawasan semua hal.', NULL, 'Adalah kategori pengetahuan dan wawasan semua hal.', '2026-04-29 21:54:01', '2026-04-29 21:54:25');

-- Dumping structure for table lanyardbagus.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int unsigned NOT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `author_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('PENDING','APPROVED','SPAM','TRASH') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_parent_id_foreign` (`parent_id`),
  KEY `comments_post_id_status_index` (`post_id`,`status`),
  KEY `comments_status_index` (`status`),
  CONSTRAINT `comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.comments: ~0 rows (approximately)

-- Dumping structure for table lanyardbagus.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table lanyardbagus.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.jobs: ~0 rows (approximately)

-- Dumping structure for table lanyardbagus.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.job_batches: ~0 rows (approximately)

-- Dumping structure for table lanyardbagus.leads
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.leads: ~2 rows (approximately)
INSERT INTO `leads` (`id`, `name`, `whatsapp`, `address`, `size`, `email`, `message`, `design_url`, `created_at`, `updated_at`) VALUES
	(8, 'Test User', '081234567890', NULL, NULL, NULL, 'Test upload', 'https://pub-45cecdcf36b74968982a45979c2b5c4f.r2.dev/leads/lead-test-Qp5IX37i.webp', '2026-04-30 09:24:02', '2026-04-30 09:24:02'),
	(9, 'Rahma Angriawan', '08967323123', NULL, NULL, 'wantek.id@gmail.com', NULL, 'https://pub-45cecdcf36b74968982a45979c2b5c4f.r2.dev/leads/lanyard-11-XwdTvD4O.webp', '2026-04-30 09:24:40', '2026-04-30 09:24:40');

-- Dumping structure for table lanyardbagus.media
CREATE TABLE IF NOT EXISTS `media` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `width` int DEFAULT NULL,
  `height` int DEFAULT NULL,
  `size` int unsigned NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.media: ~32 rows (approximately)
INSERT INTO `media` (`id`, `file_name`, `url`, `mime_type`, `width`, `height`, `size`, `alt_text`, `created_at`, `updated_at`) VALUES
	(1, 'Logo Lanyard Bagus.webp', 'http://localhost:8000/storage/media/logo-lanyard-bagus-4dd876.webp', 'image/webp', 898, 177, 53878, NULL, '2026-04-28 20:43:35', '2026-04-28 20:43:35'),
	(2, 'Favicon Lanyard Bagus.webp', 'http://localhost:8000/storage/media/favicon-lanyard-bagus-923878.webp', 'image/webp', 170, 177, 16078, NULL, '2026-04-28 20:43:46', '2026-04-28 20:43:46'),
	(3, '1-41a7d4.webp', 'http://localhost:8000/storage/media/1-41a7d4.webp', 'image/webp', 2250, 1404, 193330, NULL, '2026-04-28 21:37:51', '2026-04-28 21:37:51'),
	(4, 'lanyard 14.webp', 'http://localhost:8000/storage/media/lanyard-14-ec9231.webp', 'image/webp', 1200, 801, 17964, NULL, '2026-04-29 00:27:55', '2026-04-29 00:27:55'),
	(5, 'customer-service-people-3d-avatar-free-png.webp', 'http://localhost:8000/storage/media/customer-service-people-3d-avatar-free-png-c94e83.webp', 'image/webp', 980, 980, 354460, NULL, '2026-04-29 00:35:05', '2026-04-29 00:35:05'),
	(6, 'Logo lanyard bagus putih.webp', 'http://localhost:8000/storage/media/logo-lanyard-bagus-putih-2deb1b.webp', 'image/webp', 1309, 283, 89608, NULL, '2026-04-29 00:56:10', '2026-04-29 00:56:10'),
	(7, 'banner.webp', 'http://localhost:8000/storage/media/banner-91acfd.webp', 'image/webp', 1536, 1024, 51578, NULL, '2026-04-29 01:50:27', '2026-04-29 01:50:27'),
	(8, 'lanyard 22.webp', 'http://localhost:8000/storage/media/lanyard-22-7033f4.webp', 'image/webp', 2544, 1699, 69108, NULL, '2026-04-29 01:52:49', '2026-04-29 01:52:49'),
	(9, 'hero lanyard.webp', 'http://localhost:8000/storage/media/hero-lanyard-5bc7f4.webp', 'image/webp', 1536, 1024, 34544, NULL, '2026-04-29 01:54:31', '2026-04-29 01:54:31'),
	(10, 'gelang Silikon.webp', 'http://localhost:8000/storage/media/gelang-silikon-478750.webp', 'image/webp', 1536, 1024, 30962, NULL, '2026-04-29 03:46:18', '2026-04-29 03:46:18'),
	(11, 'lanyard hero.webp', 'http://localhost:8000/storage/media/lanyard-hero-dac089.webp', 'image/webp', 1536, 1024, 102320, NULL, '2026-04-29 05:23:56', '2026-04-29 05:23:56'),
	(13, 'f60ab2cc-fa23-42b3-87d1-06409341c92e.webp', 'http://localhost:8000/storage/media/f60ab2cc-fa23-42b3-87d1-06409341c92e.webp', 'image/webp', 1254, 1254, 106914, NULL, '2026-04-29 21:58:50', '2026-04-29 21:58:50'),
	(14, 'lanyard 17.webp', 'http://localhost:8000/storage/media/lanyard-17.webp', 'image/webp', 2544, 1699, 69108, NULL, '2026-04-29 22:01:02', '2026-04-29 22:01:02'),
	(15, 'lanyard 3.webp', 'http://localhost:8000/storage/media/lanyard-3.webp', 'image/webp', 1908, 1274, 62462, NULL, '2026-04-29 22:05:01', '2026-04-29 22:05:01'),
	(17, 'lanyard 4.webp', 'http://localhost:8000/storage/media/lanyard-4.webp', 'image/webp', 1519, 1899, 97990, 'Lanyard 4', '2026-04-30 05:08:09', '2026-04-30 05:08:09'),
	(18, 'hero lanyard.webp', 'http://localhost:8000/storage/media/hero-lanyard-uVHUw.webp', 'image/webp', 1536, 1024, 34544, 'Hero Lanyard', '2026-04-30 08:44:47', '2026-04-30 08:44:47'),
	(19, 'cta-section-ZY9O0.webp', 'http://localhost:8000/storage/media/cta-section-ZY9O0.webp', 'image/webp', 420, 420, 21718, 'CTA Section', '2026-05-01 04:28:05', '2026-05-01 04:28:05'),
	(20, 'new-logo-jne-66WqN.webp', 'http://localhost:8000/storage/media/new-logo-jne-66WqN.webp', 'image/webp', 4257, 1708, 63096, 'New Logo JNE', '2026-05-01 08:16:46', '2026-05-01 08:16:46'),
	(21, 'jt-express-logosvg-GJjXT.webp', 'http://localhost:8000/storage/media/jt-express-logosvg-GJjXT.webp', 'image/webp', 1280, 269, 20390, 'J&T Express Logo.svg', '2026-05-01 08:16:47', '2026-05-01 08:16:47'),
	(22, 'indah-cargo-rQJ7h.webp', 'http://localhost:8000/storage/media/indah-cargo-rQJ7h.webp', 'image/webp', 376, 134, 9080, 'Indah Cargo', '2026-05-01 08:16:48', '2026-05-01 08:16:48'),
	(23, 'logo-sicepat-iABco.webp', 'http://localhost:8000/storage/media/logo-sicepat-iABco.webp', 'image/webp', 921, 279, 21678, 'Logo Sicepat', '2026-05-01 08:16:48', '2026-05-01 08:16:48'),
	(24, 'aset-lanyard-1-blvlh.webp', 'http://localhost:8000/storage/media/aset-lanyard-1-blvlh.webp', 'image/webp', 1402, 1122, 101082, 'Aset Lanyard 1', '2026-05-01 08:47:53', '2026-05-01 08:47:53'),
	(25, 'aset-lanyard-2-S4NP1.webp', 'http://localhost:8000/storage/media/aset-lanyard-2-S4NP1.webp', 'image/webp', 1402, 1122, 74642, 'Aset Lanyard 2', '2026-05-01 08:49:15', '2026-05-01 08:49:15'),
	(26, 'lanyard 20.webp', 'http://localhost:8000/storage/media/lanyard-20-ffcf3.webp', 'image/webp', 2544, 1699, 70052, 'Lanyard 20', '2026-05-01 08:56:43', '2026-05-01 08:56:43'),
	(27, 'lanyard-2-sisi-iCca6.webp', 'http://localhost:8000/storage/media/lanyard-2-sisi-iCca6.webp', 'image/webp', 1402, 1122, 64566, 'Lanyard 2 Sisi', '2026-05-01 08:59:53', '2026-05-01 08:59:53'),
	(28, 'paket-lanyard-id-card-BoSmK.webp', 'http://localhost:8000/storage/media/paket-lanyard-id-card-BoSmK.webp', 'image/webp', 1402, 1122, 85394, 'Paket Lanyard Id Card', '2026-05-01 09:01:10', '2026-05-01 09:01:10'),
	(29, 'lanyard-2-setengah-senti-5WhBG.webp', 'http://localhost:8000/storage/media/lanyard-2-setengah-senti-5WhBG.webp', 'image/webp', 1402, 1122, 86864, 'Lanyard 2 Setengah Senti', '2026-05-01 09:03:46', '2026-05-01 09:03:46'),
	(30, 'lanyard-2-cm-uKPjV.webp', 'http://localhost:8000/storage/media/lanyard-2-cm-uKPjV.webp', 'image/webp', 1402, 1122, 66848, 'Lanyard 2 Cm', '2026-05-01 09:06:34', '2026-05-01 09:06:34'),
	(31, 'lanyard-1-setengah-senti-j7tlz.webp', 'http://localhost:8000/storage/media/lanyard-1-setengah-senti-j7tlz.webp', 'image/webp', 1402, 1122, 42058, 'Lanyard 1 Setengah Senti', '2026-05-01 09:07:42', '2026-05-01 09:07:42'),
	(32, 'bahan-lanyard-pilihan-deFQ6.webp', 'http://localhost:8000/storage/media/bahan-lanyard-pilihan-deFQ6.webp', 'image/webp', 1536, 1024, 125986, 'Bahan Lanyard Pilihan', '2026-05-01 09:12:08', '2026-05-01 09:12:08'),
	(34, 'pengiriman-tepat-waktu-W3msA.webp', 'http://localhost:8000/storage/media/pengiriman-tepat-waktu-W3msA.webp', 'image/webp', 1536, 1024, 64574, 'Pengiriman Tepat Waktu', '2026-05-01 09:16:24', '2026-05-01 09:16:24'),
	(35, 'warna-tajam-UyavC.webp', 'http://localhost:8000/storage/media/warna-tajam-UyavC.webp', 'image/webp', 1536, 1024, 105782, 'Warna Tajam', '2026-05-01 09:19:09', '2026-05-01 09:19:09');

-- Dumping structure for table lanyardbagus.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.migrations: ~32 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000001_create_cache_table', 1),
	(2, '0001_01_01_000002_create_jobs_table', 1),
	(3, '2026_01_01_000000_create_roles_table', 1),
	(4, '2026_01_01_000001_create_users_table', 1),
	(5, '2026_01_01_000002_create_categories_table', 1),
	(6, '2026_01_01_000003_create_posts_table', 1),
	(7, '2026_01_01_000004_create_pages_table', 1),
	(8, '2026_01_01_000005_create_media_table', 1),
	(9, '2026_01_01_000010_add_seo_fields_to_posts', 1),
	(10, '2026_01_01_000011_create_tags_table', 1),
	(11, '2026_01_01_000012_add_parent_seo_to_pages', 1),
	(12, '2026_01_01_000013_add_seo_to_categories', 1),
	(13, '2026_01_01_000014_create_comments_table', 1),
	(14, '2026_01_01_000015_add_is_indexed_to_pages', 1),
	(15, '2026_04_27_140047_create_personal_access_tokens_table', 1),
	(16, '2026_04_28_000016_create_site_settings_table', 1),
	(17, '2026_04_29_000017_create_leads_table', 1),
	(18, '2026_04_29_000018_add_logo_favicon_site_title_to_site_settings', 2),
	(19, '2026_04_29_045053_create_settings_table', 3),
	(20, '2026_04_29_062439_create_banners_table', 4),
	(21, '2026_04_29_073239_add_size_to_leads_table', 5),
	(22, '2026_04_29_081658_add_button_text_to_banners_table', 6),
	(23, '2026_04_29_085959_add_whatsapp_number_to_site_settings_table', 7),
	(24, '2026_04_30_034141_create_sessions_table', 8),
	(25, '2026_04_30_130431_add_missing_profile_fields_to_users_table', 9),
	(26, '2026_04_30_072300_add_message_to_leads_table', 10),
	(27, '2026_04_30_073100_add_address_to_leads_table', 11),
	(28, '2026_04_30_081900_add_card_image_to_banners', 12),
	(29, '2026_04_30_000019_add_homepage_data_to_site_settings_table', 13),
	(30, '2026_05_01_095231_add_bio_to_users_table', 14),
	(31, '2026_05_01_100702_make_username_unique_on_users_table', 15),
	(32, '2026_05_01_103723_add_name_to_users_table', 16);

-- Dumping structure for table lanyardbagus.pages
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `featured_image_id` int unsigned DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `is_indexed` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `idx_pages_slug` (`slug`),
  KEY `fk_pages_parent` (`parent_id`),
  KEY `fk_pages_featured_image` (`featured_image_id`),
  CONSTRAINT `fk_pages_featured_image` FOREIGN KEY (`featured_image_id`) REFERENCES `media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_pages_parent` FOREIGN KEY (`parent_id`) REFERENCES `pages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.pages: ~0 rows (approximately)

-- Dumping structure for table lanyardbagus.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table lanyardbagus.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `featured_image_id` int unsigned DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('DRAFT','PUBLISHED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `published_at` timestamp NULL DEFAULT NULL,
  `author_id` int unsigned NOT NULL,
  `category_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `idx_posts_status_created_at` (`status`,`created_at`),
  KEY `idx_posts_slug` (`slug`),
  KEY `idx_posts_author_id` (`author_id`),
  KEY `idx_posts_category_id` (`category_id`),
  KEY `fk_posts_featured_image` (`featured_image_id`),
  CONSTRAINT `fk_posts_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_posts_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `fk_posts_featured_image` FOREIGN KEY (`featured_image_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.posts: ~1 rows (approximately)
INSERT INTO `posts` (`id`, `title`, `slug`, `excerpt`, `seo_title`, `meta_description`, `featured_image_id`, `content`, `status`, `published_at`, `author_id`, `category_id`, `created_at`, `updated_at`) VALUES
	(1, 'saya adalah pos dummy', 'saya-adalah-pos-dummy', NULL, 'saya adalah pos dummy', NULL, 3, '<p>sfdsfdsfsdf,,,,,,dinigeb apanek uat kadit ayasf fdfdfdf neneks aaya adalah wahyu</p><p><img src="http://localhost:8000/storage/media/logo-lanyard-bagus-4dd876.webp" alt="Logo Lanyard Bagus" class="max-w-full h-auto rounded-lg my-4"></p><p><br></p>', 'PUBLISHED', '2026-04-28 20:33:00', 1, 1, '2026-04-28 20:33:13', '2026-04-30 05:15:59');

-- Dumping structure for table lanyardbagus.post_tag
CREATE TABLE IF NOT EXISTS `post_tag` (
  `post_id` int unsigned NOT NULL,
  `tag_id` int unsigned NOT NULL,
  PRIMARY KEY (`post_id`,`tag_id`),
  KEY `post_tag_tag_id_foreign` (`tag_id`),
  CONSTRAINT `post_tag_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `post_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.post_tag: ~0 rows (approximately)

-- Dumping structure for table lanyardbagus.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.roles: ~2 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `created_at`) VALUES
	(1, 'ADMIN', '2026-04-29 03:15:51'),
	(2, 'EDITOR', '2026-04-29 03:15:51');

-- Dumping structure for table lanyardbagus.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.sessions: ~1 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('NWnH8lKp9m9WMa1kj3CDmRSssbUafmtv62UDsMKQ', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZFkxQ2t6S2FEZlMzRGU3YUd3TjFhS1h5QXRQQUVoR0NlSmZLcjBkbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiMzhmMWUyMDE0NjM2ZmIwYzZlYTFiYzcyNjUxMzhhMjFlOGY2MTAyMmYxMzU2ODVkNDFiYzEwMWMxOWE2MTQxYiI7fQ==', 1777627165);

-- Dumping structure for table lanyardbagus.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.settings: ~10 rows (approximately)
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

-- Dumping structure for table lanyardbagus.site_settings
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_site_settings_homepage_og_image` (`homepage_og_image_id`),
  KEY `fk_site_settings_logo_image` (`logo_image_id`),
  KEY `fk_site_settings_favicon_image` (`favicon_image_id`),
  CONSTRAINT `fk_site_settings_favicon_image` FOREIGN KEY (`favicon_image_id`) REFERENCES `media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_site_settings_homepage_og_image` FOREIGN KEY (`homepage_og_image_id`) REFERENCES `media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_site_settings_logo_image` FOREIGN KEY (`logo_image_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.site_settings: ~1 rows (approximately)
INSERT INTO `site_settings` (`id`, `site_title`, `whatsapp_number`, `homepage_seo_title`, `homepage_meta_description`, `homepage_og_image_id`, `logo_image_id`, `favicon_image_id`, `homepage_data`, `google_site_verification`, `bing_site_verification`, `created_at`, `updated_at`) VALUES
	(1, 'Lanyard Bagus', '089674256043', 'Pabrik Lanyard Custom | Desain Custom Sendiri', 'Kami hadir untuk memenuhi kebutuhan lanyard Anda, dari desain hingga produksi dengan hasil rapi, kuat, dan siap digunakan di berbagai acara.', 1, 1, 2, '{"cta": {"title": "Setiap Tim Hebat Memiliki Cerita.\\nMari Cetak Cerita Anda Bersama Kami.", "description": "Jangan biarkan desain terbaik Anda rusak oleh vendor yang salah. Hubungi kami hari ini untuk konsultasi bahan, desain gratis, dan amankan jadwal produksi Anda.", "primary_href": "whatsapp", "primary_label": "Pesan via WhatsApp", "secondary_href": "#produk", "secondary_label": "Lihat Daftar Harga"}, "faq": {"badge": "Tanya Jawab", "items": [{"answer": "Kami menerima pemesanan dalam jumlah fleksibel. Untuk mendapatkan harga terbaik dan efisiensi produksi, kami merekomendasikan minimum order sebanyak 50 pcs. Namun, jangan ragu untuk berkonsultasi jika kebutuhan Anda di bawah jumlah tersebut.", "question": "Berapa minimum pemesanan lanyard custom?"}, {"answer": "Standar waktu produksi kami berkisar antara 3 hingga 7 hari kerja tergantung dari jumlah pesanan dan antrean produksi. Kami juga menyediakan layanan prioritas (express) untuk kebutuhan mendesak dengan biaya tambahan.", "question": "Berapa lama waktu pengerjaan produksi?"}, {"answer": "Tentu saja! Tim desain internal kami siap membantu memvisualisasikan ide Anda. Layanan desain dasar ini kami berikan secara gratis khusus untuk pelanggan yang telah melakukan pembayaran uang muka pesanan.", "question": "Apakah saya bisa meminta dibuatkan desain?"}, {"answer": "Untuk hasil cetak paling tajam dengan kenyamanan maksimal, kami sangat menyarankan bahan Tissue (Polyester halus) dipadukan dengan teknik Printing Premium. Bahan ini sangat lembut di leher dan mampu menyerap tinta sublimasi dengan sempurna.", "question": "Bahan apa yang paling direkomendasikan?"}], "title": "Pertanyaan Seputar Lanyard"}, "hero": {"badge": "TALI BERKUALITAS PREMIUM", "title": "Selamat Datang di Lanyard Bagus", "image_url": "http://localhost:8000/storage/media/banner-91acfd.webp", "button_url": "#cerita", "card_badge": "Premium Quality", "button_text": "Lihat Cerita Kami", "description": "Bayangkan seluruh tim Anda berjalan dengan penuh kebanggaan, mengenakan lanyard yang mewakili profesionalisme dan visi besar perusahaan.", "card_image_url": "/storage/media/hero-lanyard-5bc7f4.webp", "secondary_button_url": "#hubungi", "secondary_button_text": "Konsultasi Desain"}, "story": {"badge": "Cara Kami Bekerja", "steps": [{"title": "Bahan Pilihan yang Menghargai Kenyamanan Tim Anda", "number": "01", "image_url": "http://localhost:8000/storage/media/bahan-lanyard-pilihan-deFQ6.webp", "description": "Kami memilih serat kain yang halus di kulit dan nyaman dipakai seharian."}, {"title": "Warna Cerah yang Meneriakkan Karakter Brand Anda", "number": "02", "image_url": "http://localhost:8000/storage/media/warna-tajam-UyavC.webp", "description": "Setiap detail warna diterjemahkan dengan presisi mutlak agar identitas Anda tetap kuat."}, {"title": "Tiba Tepat Waktu, Jauh Sebelum Momen Besar Dimulai", "number": "03", "image_url": "http://localhost:8000/storage/media/pengiriman-tepat-waktu-W3msA.webp", "description": "Sistem produksi kami dirancang untuk satu janji: selesai tepat waktu dan tanpa kompromi."}], "title": "Perjalanan Menciptakan Kebanggaan", "description": "Bagi kami, ini bukan tentang seberapa banyak kami mencetak, tapi seberapa sempurna setiap helai tali mewakili nama besar Anda."}, "footer": {"social_links": [], "layanan_links": [{"href": "/tentang", "label": "Tentang Kami"}, {"href": "/kontak", "label": "Kontak Kami"}, {"href": "/pengiriman", "label": "Info Pengiriman"}, {"href": "/kebijakan", "label": "Kebijakan & Syarat"}], "layanan_title": "Profil", "copyright_text": "Dibuat dengan dedikasi untuk branding identitas.", "insights_links": [{"href": "/blog", "label": "Semua Artikel"}, {"href": "/faq", "label": "FAQ"}], "insights_title": "Insights", "whatsapp_label": "Hubungi CS", "navigation_links": [{"href": "/produk", "label": "Produk"}, {"href": "/jenis", "label": "Jenis"}, {"href": "/harga", "label": "Daftar Harga"}], "navigation_title": "Navigasi"}, "contact": {"badge": "Pesan Online", "hints": {"file": "PNG, JPG, WebP — Maks. 5MB"}, "steps": {"1": "Data Diri", "2": "Kontak", "3": "Detail"}, "title": "Mulai Pesanan Anda", "labels": {"file": "Upload Referensi Desain", "name": "Nama Lengkap", "email": "Email", "address": "Alamat Lengkap", "message": "Pesan", "whatsapp": "No. WhatsApp"}, "buttons": {"back": "Kembali", "file": "Klik atau seret gambar ke sini", "next": "Lanjutkan", "submit": "Kirim Pesanan"}, "description": "Isi formulir di bawah dan tim kami akan segera menghubungi Anda.", "placeholders": {"name": "Masukkan nama lengkap Anda", "email": "email@contoh.com", "address": "Alamat pengiriman Anda...", "message": "Ceritakan kebutuhan lanyard Anda...", "whatsapp": "08xx-xxxx-xxxx"}}, "problem": {"badge": "MENGAPA KAMI HADIR?", "items": [{"icon": "calendar-x", "title": "Acara Sudah Dekat, Tali Belum Jadi", "description": "Ketegangan saat vendor berjanji palsu dan membiarkan panitia cemas di hari-hari krusial."}, {"icon": "droplet", "title": "Warna Pudar yang Merusak Penampilan", "description": "Anda sudah susah payah mendesain logo perusahaan yang indah, namun hasil cetaknya buram dan luntur."}, {"icon": "frown", "title": "Bahan Kaku dan Membuat Gatal", "description": "Memaksa tim Anda memakai lanyard berbahan kasar seharian, membuat mereka tidak nyaman."}], "title": "Membangun Identitas Seharusnya Tidak Membuat Anda Frustrasi", "description": "Kami tahu betapa melelahkannya mencari vendor yang benar-benar peduli pada detail acara Anda."}, "customize": {"badge": "Koleksi Kami", "items": [{"href": "/produk", "icon": "printer", "title": "Printing Premium", "image_url": "http://localhost:8000/storage/media/aset-lanyard-2-S4NP1.webp", "description": "Pilihan terpopuler. Mampu menampilkan gradasi warna dan desain kompleks tanpa batas dengan presisi luar biasa."}, {"href": "/produk#paketidentitas", "icon": "badge-check", "title": "Paket Lanyard + ID Card", "image_url": "http://localhost:8000/storage/media/paket-lanyard-id-card-BoSmK.webp", "description": "Harmonisasi sempurna. Tali dan kartu identitas yang dirancang senada untuk menciptakan kesatuan visual yang solid."}, {"href": "/produk#2sisi", "icon": "layers", "title": "Lanyard 2 Sisi", "image_url": "http://localhost:8000/storage/media/lanyard-2-sisi-iCca6.webp", "description": "Maksimalkan area desain Anda dengan cetakan bolak-balik. Sempurna untuk visibilitas brand ekstra dari segala sudut."}], "title": "Pilih Kanvas untuk Identitas Anda"}, "header_cta": {"href": "/kontak", "label": "Pesan Sekarang"}, "navigation_links": [{"href": "/", "label": "Beranda"}, {"href": "/jenis", "label": "Jenis"}, {"href": "/produk", "label": "Produk"}, {"href": "/harga", "label": "Harga"}, {"href": "/faq", "label": "FAQ"}]}', NULL, NULL, '2026-04-28 20:16:43', '2026-05-01 09:19:20');

-- Dumping structure for table lanyardbagus.tags
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.tags: ~1 rows (approximately)
INSERT INTO `tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
	(1, 'tagtig', 'tagtig', '2026-04-29 21:55:41', '2026-04-29 21:55:41');

-- Dumping structure for table lanyardbagus.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_me` tinyint(1) NOT NULL DEFAULT '0',
  `role_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `idx_users_role_id` (`role_id`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lanyardbagus.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `username`, `phone_number`, `avatar_url`, `bio`, `password`, `remember_me`, `role_id`, `created_at`, `updated_at`) VALUES
	(1, 'Rama Angriawan', 'admin@local.test', 'Rama Angriawan', '089674256043', 'http://localhost:8000/storage/media/customer-service-people-3d-avatar-free-png-c94e83.webp', 'Saya penggiat AI dan SEO sejak 9 tahun yang lalu hingga saat ini.', '$argon2id$v=19$m=65536,t=4,p=1$REIyTGpMallZTDNjMnEvSA$gHHk6duyigGspKWS2Rf1YwGgEecFuYy/W569k+wOXvA', 0, 1, '2026-04-28 20:16:01', '2026-05-01 03:38:32'),
	(2, 'Rahma Angriawan', 'admin@example.com', 'Rahma Angriawan', '089674256043', NULL, 'Saya penggiat AI dan SEO sejak 9 tahun yang lalu hingga saat ini.', '$argon2id$v=19$m=65536,t=4,p=1$aEJmV1hDSDZZOFRTRWdFeg$T4F1L/pS8/tbVMV6k28cNk2QC887BnotrwzziU3wa14', 0, 1, '2026-04-29 20:05:32', '2026-05-01 03:38:32'),
	(3, 'Admin 1', 'admin@lanyardbagus.com', 'Admin 1', NULL, NULL, NULL, '$argon2id$v=19$m=65536,t=4,p=1$dXdSOFFiWE94dURSTlRDRQ$swzMeGtRUlF3I/Ao4Q4vvIbDL7ZjxiRW0pnSV2LkHwg', 0, 1, '2026-04-29 20:05:33', '2026-05-01 03:38:32');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
SET FOREIGN_KEY_CHECKS = 1;