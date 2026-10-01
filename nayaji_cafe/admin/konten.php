<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pageTitle = 'Konten Website - NayaJi Cafe';
$content = $pdo->query('SELECT * FROM website_content WHERE id = 1')->fetch();
if (!$content) {
    set_flash('Konten website belum tersedia. Jalankan database.sql untuk membuat data awal.', 'error');
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit();
}
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/admin_nav.php';
?>
<section class="admin-page content-admin-page">
    <div class="admin-heading">
        <div><p class="eyebrow">TAMPILAN PUBLIK</p><h1>Konten website</h1><p>Perubahan disimpan ke database dan langsung digunakan halaman publik.</p></div>
    </div>
    <form class="admin-form content-form" method="POST" action="<?= BASE_URL ?>/admin/proses_konten.php" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <section class="content-editor-section" id="hero">
            <div class="content-section-heading"><p class="eyebrow">01 / HERO</p><h2>Bagian utama</h2></div>
            <div class="form-grid">
                <label class="form-field"><span>Judul utama</span><input name="hero_title" maxlength="180" value="<?= escape_html($content['hero_title']) ?>" required></label>
                <label class="form-field"><span>Subtitle / label</span><input name="hero_subtitle" maxlength="180" value="<?= escape_html($content['hero_subtitle']) ?>" required></label>
                <label class="form-field form-field-wide"><span>Deskripsi</span><textarea name="hero_description" rows="4" maxlength="5000" required><?= escape_html($content['hero_description']) ?></textarea></label>
                <label class="form-field"><span>Teks tombol CTA</span><input name="hero_cta_label" maxlength="60" value="<?= escape_html($content['hero_cta_label']) ?>" required></label>
                <label class="form-field"><span>Tujuan tombol (path internal, mis. /produk.php)</span><input name="hero_cta_url" maxlength="255" value="<?= escape_html($content['hero_cta_url']) ?>" required></label>
                <label class="form-field form-field-wide"><span>Ganti foto hero (JPG/JPEG/PNG, maks. 2 MB)</span><input type="file" name="hero_image" accept="image/jpeg,image/png"></label>
            </div>
            <div class="current-image"><img src="<?= escape_html(website_image_url($content['hero_image'])) ?>" alt="Foto hero saat ini"><span>Foto hero saat ini</span></div>
        </section>

        <section class="content-editor-section" id="about">
            <div class="content-section-heading"><p class="eyebrow">02 / TENTANG KAMI</p><h2>Cerita NayaJi</h2></div>
            <div class="form-grid">
                <label class="form-field form-field-wide"><span>Judul tentang kami</span><input name="about_title" maxlength="180" value="<?= escape_html($content['about_title']) ?>" required></label>
                <label class="form-field form-field-wide"><span>Deskripsi cafe</span><textarea name="about_description" rows="5" maxlength="5000" required><?= escape_html($content['about_description']) ?></textarea></label>
                <label class="form-field form-field-wide"><span>Ganti foto tentang kami (JPG/JPEG/PNG, maks. 2 MB)</span><input type="file" name="about_image" accept="image/jpeg,image/png"></label>
            </div>
            <div class="current-image"><img src="<?= escape_html(website_image_url($content['about_image'])) ?>" alt="Foto About saat ini"><span>Foto tentang kami saat ini</span></div>
        </section>

        <section class="content-editor-section" id="kontak">
            <div class="content-section-heading"><p class="eyebrow">03 / KONTAK</p><h2>Informasi cafe</h2></div>
            <div class="form-grid">
                <label class="form-field"><span>Nomor WhatsApp</span><input name="whatsapp" maxlength="30" value="<?= escape_html($content['whatsapp']) ?>" placeholder="08123456789"></label>
                <label class="form-field"><span>Email</span><input type="email" name="email" maxlength="150" value="<?= escape_html($content['email']) ?>" placeholder="halo@nayajicafe.id"></label>
                <label class="form-field"><span>Instagram</span><input name="instagram" maxlength="100" value="<?= escape_html($content['instagram']) ?>" placeholder="@nayajicafe"></label>
                <label class="form-field"><span>Jam buka</span><input name="jam_buka" maxlength="120" value="<?= escape_html($content['jam_buka']) ?>" placeholder="Setiap hari, 10.00 - 22.00"></label>
                <label class="form-field form-field-wide"><span>Alamat</span><input name="alamat" maxlength="255" value="<?= escape_html($content['alamat']) ?>" placeholder="Jember, Jawa Timur"></label>
            </div>
        </section>
        <div class="form-actions content-save-row" id="pengaturan">
            <button class="button button-primary" type="submit">Simpan Konten Website</button>
            <a class="button button-quiet" href="<?= BASE_URL ?>/" target="_blank" rel="noopener">Lihat Website <span aria-hidden="true">&#8599;</span></a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>