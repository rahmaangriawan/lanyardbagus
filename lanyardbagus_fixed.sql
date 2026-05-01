SET FOREIGN_KEY_CHECKS = 0;
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 1. Roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `created_at`) VALUES (1, 'ADMIN', '2026-04-29 03:15:51'), (2, 'EDITOR', '2026-04-29 03:15:51');

-- 2. Users
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

INSERT INTO `users` (`id`, `name`, `email`, `username`, `phone_number`, `avatar_url`, `bio`, `password`, `remember_me`, `role_id`, `created_at`, `updated_at`) VALUES
	(1, 'Rama Angriawan', 'admin@local.test', 'Rama Angriawan', '089674256043', 'http://localhost:8000/storage/media/customer-service-people-3d-avatar-free-png-c94e83.webp', 'Saya penggiat AI dan SEO sejak 9 tahun yang lalu hingga saat ini.', '$argon2id$v=19$m=65536,t=4,p=1$REIyTGpMallZTDNjMnEvSA$gHHk6duyigGspKWS2Rf1YwGgEecFuYy/W569k+wOXvA', 0, 1, '2026-04-28 20:16:01', '2026-05-01 03:38:32'),
	(2, 'Rahma Angriawan', 'admin@example.com', 'Rahma Angriawan', '089674256043', NULL, 'Saya penggiat AI dan SEO sejak 9 tahun yang lalu hingga saat ini.', '$argon2id$v=19$m=65536,t=4,p=1$aEJmV1hDSDZZOFRTRWdFeg$T4F1L/pS8/tbVMV6k28cNk2QC887BnotrwzziU3wa14', 0, 1, '2026-04-29 20:05:32', '2026-05-01 03:38:32'),
	(3, 'Admin 1', 'admin@lanyardbagus.com', 'Admin 1', NULL, NULL, NULL, '$argon2id$v=19$m=65536,t=4,p=1$dXdSOFFiWE94dURSTlRDRQ$swzMeGtRUlF3I/Ao4Q4vvIbDL7ZjxiRW0pnSV2LkHwg', 0, 1, '2026-04-29 20:05:33', '2026-05-01 03:38:32');

-- 3. Categories
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

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `seo_title`, `meta_description`, `created_at`, `updated_at`) VALUES (1, 'Pengetahuan', 'pengetahuan', 'Adalah kategori pengetahuan dan wawasan semua hal.', NULL, 'Adalah kategori pengetahuan dan wawasan semua hal.', '2026-04-29 21:54:01', '2026-04-29 21:54:25');

-- 4. Media
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

INSERT INTO `media` (`id`, `file_name`, `url`, `mime_type`, `width`, `height`, `size`, `alt_text`, `created_at`, `updated_at`) VALUES
	(1, 'Logo Lanyard Bagus.webp', 'http://localhost:8000/storage/media/logo-lanyard-bagus-4dd876.webp', 'image/webp', 898, 177, 53878, NULL, '2026-04-28 20:43:35', '2026-04-28 20:43:35'),
	(2, 'Favicon Lanyard Bagus.webp', 'http://localhost:8000/storage/media/favicon-lanyard-bagus-923878.webp', 'image/webp', 170, 177, 16078, NULL, '2026-04-28 20:43:46', '2026-04-28 20:43:46'),
	(3, '1-41a7d4.webp', 'http://localhost:8000/storage/media/1-41a7d4.webp', 'image/webp', 2250, 1404, 193330, NULL, '2026-04-28 21:37:51', '2026-04-28 21:37:51'),
	(19, 'cta-section-ZY9O0.webp', 'http://localhost:8000/storage/media/cta-section-ZY9O0.webp', 'image/webp', 420, 420, 21718, 'CTA Section', '2026-05-01 04:28:05', '2026-05-01 04:28:05');

-- 5. Posts
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

INSERT INTO `posts` (`id`, `title`, `slug`, `excerpt`, `seo_title`, `meta_description`, `featured_image_id`, `content`, `status`, `published_at`, `author_id`, `category_id`, `created_at`, `updated_at`) VALUES (1, 'saya adalah pos dummy', 'saya-adalah-pos-dummy', NULL, 'saya adalah pos dummy', NULL, 3, '<p>Dummy Content</p>', 'PUBLISHED', '2026-04-28 20:33:00', 1, 1, '2026-04-28 20:33:13', '2026-04-30 05:15:59');

-- 6. Comments
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

-- 7. Banners
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

INSERT INTO `banners` (`id`, `title`, `description`, `badge`, `image_url`, `card_image_url`, `link_url`, `button_text`, `order`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'Selamat datang di Lanyardbagus', 'Tinggal kirim desain atau brief, sisanya kami yang handle.', NULL, 'http://localhost:8000/storage/media/lanyard-hero-dac089.webp', 'http://localhost:8000/storage/media/hero-lanyard-uVHUw.webp', NULL, NULL, 0, 1, '2026-04-29 00:27:26', '2026-04-30 08:44:53');

-- 8. Other tables...
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

INSERT INTO `site_settings` (`id`, `site_title`, `whatsapp_number`, `homepage_seo_title`, `homepage_meta_description`, `homepage_og_image_id`, `logo_image_id`, `favicon_image_id`, `homepage_data`, `created_at`, `updated_at`) VALUES (1, 'Lanyard Bagus', '089674256043', 'Pabrik Lanyard Custom', 'Deskripsi...', 1, 1, 2, '{}', '2026-04-28 20:16:43', '2026-05-01 09:19:20');

SET FOREIGN_KEY_CHECKS = 1;
