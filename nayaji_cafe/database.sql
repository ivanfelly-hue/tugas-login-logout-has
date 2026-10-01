CREATE DATABASE IF NOT EXISTS nayaji_cafe
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE nayaji_cafe;

CREATE TABLE IF NOT EXISTS produk (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    deskripsi TEXT NOT NULL,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    stok INT NOT NULL DEFAULT 0,
    gambar VARCHAR(255) NOT NULL DEFAULT '',
    featured TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_produk_nama (nama),
    INDEX idx_produk_kategori (kategori)
) ENGINE=InnoDB;

SET @featured_column_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'produk'
      AND COLUMN_NAME = 'featured'
);
SET @featured_migration = IF(
    @featured_column_exists = 0,
    'ALTER TABLE produk ADD COLUMN featured TINYINT(1) NOT NULL DEFAULT 0 AFTER gambar',
    'SELECT 1'
);
PREPARE featured_migration_statement FROM @featured_migration;
EXECUTE featured_migration_statement;
DEALLOCATE PREPARE featured_migration_statement;

CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS website_content (
    id TINYINT UNSIGNED PRIMARY KEY,
    hero_title VARCHAR(180) NOT NULL,
    hero_subtitle VARCHAR(180) NOT NULL,
    hero_description TEXT NOT NULL,
    hero_image VARCHAR(255) NOT NULL DEFAULT '',
    hero_cta_label VARCHAR(60) NOT NULL DEFAULT 'Lihat Menu',
    hero_cta_url VARCHAR(255) NOT NULL DEFAULT '/produk.php',
    about_title VARCHAR(180) NOT NULL,
    about_description TEXT NOT NULL,
    about_image VARCHAR(255) NOT NULL DEFAULT '',
    whatsapp VARCHAR(30) NOT NULL DEFAULT '',
    email VARCHAR(150) NOT NULL DEFAULT '',
    instagram VARCHAR(100) NOT NULL DEFAULT '',
    alamat VARCHAR(255) NOT NULL DEFAULT '',
    jam_buka VARCHAR(120) NOT NULL DEFAULT '',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT IGNORE INTO website_content (
    id, hero_title, hero_subtitle, hero_description, hero_image, hero_cta_label, hero_cta_url,
    about_title, about_description, about_image, whatsapp, email, instagram, alamat, jam_buka
) VALUES (
    1,
    'Tempat singgah, cerita bertumbuh.',
    'KOPI, CERITA, DAN SUASANA',
    'Racikan yang dibuat dengan hati, teman yang selalu punya waktu, dan suasana yang membuat hari terasa lebih ringan.',
    'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1400&q=90',
    'Temukan menu',
    '/produk.php',
    'Datang untuk kopi. Tinggal lebih lama.',
    'NayaJi adalah jeda kecil di tengah hari yang sibuk. Kami percaya secangkir yang baik selalu terasa lebih lengkap saat dinikmati bersama.',
    'https://images.unsplash.com/photo-1445116572660-236099ec97a0?auto=format&fit=crop&w=1200&q=85',
    '08123456789',
    'halo@nayajicafe.id',
    '@nayajicafe',
    'Jember, Jawa Timur',
    'Setiap hari, 10.00 - 22.00'
);

INSERT INTO produk (nama, kategori, deskripsi, harga, stok, gambar, featured)
SELECT 'Kopi Susu NayaJi', 'Kopi', 'Espresso, susu segar, dan gula aren dengan rasa yang seimbang.', 28000, 24,
       'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=1000&q=85'
    , 1
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE nama = 'Kopi Susu NayaJi');

INSERT INTO produk (nama, kategori, deskripsi, harga, stok, gambar, featured)
SELECT 'Cafe Latte', 'Kopi', 'Espresso lembut berpadu dengan susu steamed dan foam tipis.', 30000, 18,
    'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=1000&q=85', 1
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE nama = 'Cafe Latte');

INSERT INTO produk (nama, kategori, deskripsi, harga, stok, gambar, featured)
SELECT 'Matcha Cloud', 'Non-kopi', 'Matcha pilihan dengan susu creamy dan lapisan foam lembut.', 32000, 12,
    'https://images.unsplash.com/photo-1515823064-d6e0c04616a7?auto=format&fit=crop&w=1000&q=85', 1
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE nama = 'Matcha Cloud');

INSERT INTO produk (nama, kategori, deskripsi, harga, stok, gambar, featured)
SELECT 'Croissant Butter', 'Pastry', 'Croissant berlapis dengan aroma butter dan tekstur renyah.', 26000, 10,
    'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=1000&q=85', 0
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE nama = 'Croissant Butter');

INSERT INTO produk (nama, kategori, deskripsi, harga, stok, gambar, featured)
SELECT 'Chocolate Brownie', 'Pastry', 'Brownie cokelat pekat dengan bagian tengah yang fudgy.', 24000, 8,
    'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=1000&q=85', 0
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE nama = 'Chocolate Brownie');