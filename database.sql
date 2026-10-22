-- =====================================================================
-- Sumber Jaya — Database Schema & Seed
-- Import:  C:/xampp/mysql/bin/mysql.exe -u root sumber_jaya < database.sql
-- atau lewat phpMyAdmin (Import) setelah membuat database `sumber_jaya`.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `sumber_jaya`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sumber_jaya`;

-- ---------------------------------------------------------------------
-- Kategori produk
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(100) NOT NULL,
  `slug`        VARCHAR(120) NOT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `image_url`   VARCHAR(500) NULL DEFAULT NULL,
  `created_at`  DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Slider hero beranda (dikelola dari admin > Slider Beranda)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `hero_slides` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `label`      VARCHAR(100) NULL DEFAULT NULL,
  `title`      VARCHAR(150) NOT NULL,
  `text`       VARCHAR(300) NULL DEFAULT NULL,
  `image_url`  VARCHAR(500) NULL DEFAULT NULL,
  `cta_text`   VARCHAR(50) NULL DEFAULT NULL,
  `cta_url`    VARCHAR(255) NULL DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active`  TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_hero_slides_order` (`sort_order`, `is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Produk (harga & stok ada di varian, produk wajib punya minimal 1 varian)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` INT UNSIGNED NOT NULL,
  `name`        VARCHAR(150) NOT NULL,
  `slug`        VARCHAR(170) NOT NULL,
  `description` TEXT NULL,
  `image_url`   VARCHAR(500) NULL DEFAULT NULL,
  `is_active`   TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`  DATETIME NULL DEFAULT NULL,
  `updated_at`  DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_products_slug` (`slug`),
  KEY `idx_products_category` (`category_id`),
  CONSTRAINT `fk_products_category`
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Varian produk (ukuran) — memegang harga & stok
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_variants` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED NOT NULL,
  `label`      VARCHAR(50) NOT NULL,
  `price`      INT UNSIGNED NOT NULL DEFAULT 0,
  `stock`      INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_variants_product` (`product_id`),
  CONSTRAINT `fk_variants_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Identitas toko (hanya 1 baris, id = 1)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
  `id`              TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `store_name`      VARCHAR(100) NOT NULL DEFAULT 'Sumber Jaya',
  `whatsapp_number` VARCHAR(30) NOT NULL DEFAULT '6281234567890', -- format internasional, tanpa +
  `phone`           VARCHAR(30) NULL DEFAULT NULL,
  `address`         VARCHAR(255) NULL DEFAULT NULL,
  `email`           VARCHAR(100) NULL DEFAULT NULL,
  `about_text`      TEXT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Admin
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`      VARCHAR(50) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `name`          VARCHAR(100) NULL DEFAULT NULL,
  `created_at`    DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_admins_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Tracking kunjungan (cookie sj_vid -> visitor_id unik per browser).
-- product_id NULL = halaman non-produk. Tidak ada FK agar riwayat
-- tetap tersimpan walaupun produk dihapus.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `page_views` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `visitor_id` CHAR(32) NOT NULL,
  `product_id` INT UNSIGNED NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_page_views_created` (`created_at`),
  KEY `idx_page_views_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- SEED
-- =====================================================================

-- Admin: admin / admin123 (ganti password setelah login pertama)
INSERT INTO `admins` (`username`, `password_hash`, `name`, `created_at`) VALUES
('admin', '$2y$10$ALbmaQngBmJL6YsrZw6H3eVV.lseUKLFm55Tf3RatpgXb6Tv3CMqC', 'Administrator', NOW());

-- Identitas toko (nomor WhatsApp placeholder — ganti lewat menu Pengaturan)
INSERT INTO `settings` (`id`, `store_name`, `whatsapp_number`, `phone`, `address`, `email`, `about_text`) VALUES
(1, 'Sumber Jaya', '6281234567890', '021-555-0123', 'Jl. Kopi Raya No. 12, Jakarta', 'hello@sumberjaya.id',
 'Sumber Jaya adalah toko kopi yang menyediakan biji kopi pilihan dari petani lokal Indonesia. Kami memanggang (roasting) segar setiap minggu dan mengirimkannya langsung ke rumah Anda. Dari Gayo hingga Toraja, temukan kopi favoritmu di sini.');

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image_url`, `created_at`) VALUES
(1, 'Arabika',   'arabika',   'Biji kopi arabika dengan aroma dan rasa yang kompleks.', 'assets/img/produk/arabika-gayo.jpg', NOW()),
(2, 'Robusta',   'robusta',   'Kopi robusta dengan body tebal dan kafein tinggi.',     'https://placehold.co/600x400/4e342e/ffffff?text=Robusta+Jawa', NOW()),
(3, 'Blend',     'blend',     'Campuran biji kopi untuk rasa yang seimbang.',           'https://placehold.co/600x400/d7ccc8/4e342e?text=Blend+Kopi+Susu', NOW()),
(4, 'Kopi Instan', 'kopi-instan', 'Praktis dan cepat diseduh untuk keseharian.',        'https://placehold.co/600x400/cd853f/ffffff?text=Kopi+Instan', NOW());

INSERT INTO `hero_slides` (`label`, `title`, `text`, `image_url`, `cta_text`, `cta_url`, `sort_order`, `is_active`, `created_at`) VALUES
('Roasted fresh every week', 'Kopi pilihan dari petani lokal Indonesia', 'Dari Gayo sampai Toraja. Pilih ukuran favoritmu, tambahkan ke keranjang, lalu checkout langsung lewat WhatsApp.', NULL, 'Lihat Katalog', 'products', 1, 1, NOW()),
('Dari dataran tinggi', 'Arabika, Robusta, sampai kopi Besemah', 'Setiap biji dipilih dari petani lokal Indonesia dan disangrai segar agar rasanya konsisten di setiap seduhan.', NULL, 'Tentang Kami', 'about', 2, 1, NOW()),
('Pesan tanpa ribet', 'Checkout langsung lewat WhatsApp', 'Pilih varian favoritmu, tambahkan ke keranjang, dan pesan dalam hitungan menit - tanpa perlu daftar akun.', NULL, 'Mulai Belanja', 'products', 3, 1, NOW());

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `image_url`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Arabika Gayo', 'arabika-gayo', 'Biji kopi arabika dari dataran tinggi Gayo, Aceh. Rasa cokelat gelap dengan aroma bunga dan sedikit keasaman jeruk.', 'https://placehold.co/600x400/6f4e37/ffffff?text=Arabika+Gayo', 1, NOW(), NOW()),
(2, 1, 'Arabika Kintamani', 'arabika-kintamani', 'Arabika Bali Kintamani dengan keasaman jeruk mandarin yang segar dan aroma jeruk.', 'https://placehold.co/600x400/8b5a2b/ffffff?text=Arabika+Kintamani', 1, NOW(), NOW()),
(3, 1, 'Arabika Toraja', 'arabika-toraja', 'Arabika Sulawesi Toraja, body tebal dengan rasa kacang dan rempah.', 'https://placehold.co/600x400/a0522d/ffffff?text=Arabika+Toraja', 1, NOW(), NOW()),
(4, 2, 'Robusta Jawa', 'robusta-jawa', 'Robusta Jawa dengan rasa kacang panggang dan pahit khas, cocok untuk espresso atau tubruk.', 'https://placehold.co/600x400/4e342e/ffffff?text=Robusta+Jawa', 1, NOW(), NOW()),
(5, 2, 'Robusta Lampung', 'robusta-lampung', 'Robusta Lampung full body, kafein tinggi, cocok untuk kopi susu.', 'https://placehold.co/600x400/3e2723/ffffff?text=Robusta+Lampung', 1, NOW(), NOW()),
(6, 3, 'Blend Pagi Hari', 'blend-pagi-hari', 'Camparan arabika dan robusta dengan rasa seimbang, pas untuk menemani pagi.', 'https://placehold.co/600x400/bcaaa4/ffffff?text=Blend+Pagi+Hari', 1, NOW(), NOW()),
(7, 3, 'Blend Kopi Susu', 'blend-kopi-susi', 'Blend khusus kopi susu: manis karamel dan body creamy saat dicampur susu.', 'https://placehold.co/600x400/d7ccc8/4e342e?text=Blend+Kopi+Susu', 1, NOW(), NOW()),
(8, 4, 'Kopi Instan Arabika', 'kopi-instan-arabika', 'Kopi instan arabika dalam sachet, praktis untuk di mana saja.', 'https://placehold.co/600x400/cd853f/ffffff?text=Kopi+Instan', 1, NOW(), NOW());

INSERT INTO `product_variants` (`product_id`, `label`, `price`, `stock`) VALUES
(1, '100g', 45000, 25),
(1, '250g', 95000, 18),
(1, '500g', 180000, 10),
(2, '250g', 85000, 20),
(2, '500g', 160000, 12),
(3, '250g', 90000, 15),
(3, '500g', 170000, 8),
(4, '250g', 40000, 30),
(4, '500g', 75000, 22),
(5, '250g', 38000, 5),
(5, '500g', 70000, 16),
(6, '250g', 60000, 20),
(6, '500g', 115000, 14),
(7, '250g', 65000, 3),
(7, '500g', 125000, 9),
(8, '10 sachet', 15000, 40),
(8, '20 sachet', 28000, 35);

-- ---------------------------------------------------------------------
-- Upgrade database yang sudah ter-install (jalankan sekali):
--
--   ALTER TABLE `categories`
--     ADD COLUMN `image_url` VARCHAR(500) NULL DEFAULT NULL AFTER `description`;
--
-- Gambar kategori bisa diisi manual lewat admin > Kategori, atau disalin
-- dari gambar produk pertama tiap kategori:
--
--   UPDATE `categories` c
--     SET c.image_url = (
--       SELECT p.image_url FROM (SELECT * FROM `products`) p
--       WHERE p.category_id = c.id AND p.image_url IS NOT NULL AND p.image_url <> ''
--       ORDER BY p.id LIMIT 1
--     );
-- ---------------------------------------------------------------------
