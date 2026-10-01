<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'NayaJi Cafe - Kopi, Cerita, dan Suasana';
$websiteContent = $pdo->query('SELECT * FROM website_content WHERE id = 1')->fetch();
$featuredProducts = $pdo->query('SELECT id, nama, kategori, deskripsi, harga, gambar FROM produk WHERE featured = 1 ORDER BY created_at DESC, id DESC LIMIT 3')->fetchAll();
$heroTitleLines = preg_split('/\R/', $websiteContent['hero_title'] ?? 'Tempat singgah, cerita bertumbuh.', 2);
require __DIR__ . '/includes/header.php';
?>
<section class="hero" id="beranda">
    <div class="hero-copy">
        <p class="eyebrow"><span></span> <?= escape_html($websiteContent['hero_subtitle'] ?? 'KOPI, CERITA, DAN SUASANA') ?></p>
        <h1><?= escape_html($heroTitleLines[0]) ?><?php if (isset($heroTitleLines[1])): ?><br><em><?= escape_html($heroTitleLines[1]) ?></em><?php endif; ?></h1>
        <p class="hero-description"><?= escape_html($websiteContent['hero_description'] ?? '') ?></p>
        <div class="hero-actions">
            <a class="button button-primary" href="<?= escape_html(BASE_URL . ($websiteContent['hero_cta_url'] ?? '/produk.php')) ?>"><?= escape_html($websiteContent['hero_cta_label'] ?? 'Temukan menu') ?> <span aria-hidden="true">&#8599;</span></a>
            <a class="text-link" href="<?= BASE_URL ?>/tentang.php">Kenali NayaJi</a>
        </div>
        <div class="hero-note"><span class="note-dot"></span> Diseduh segar setiap hari</div>
    </div>
    <div class="hero-media">
        <img src="<?= escape_html(website_image_url($websiteContent['hero_image'] ?? '')) ?>" alt="<?= escape_html($websiteContent['hero_title'] ?? 'NayaJi Cafe') ?>" fetchpriority="high">
        <div class="hero-caption"><span>01 / 03</span><strong>Ruang nyaman, rasa yang akrab.</strong></div>
    </div>
</section>

<section class="intro-strip">
    <p>GOOD COFFEE <span>&bull;</span> SLOW MOMENTS <span>&bull;</span> GOOD COMPANY</p>
    <span class="strip-mark">N</span>
</section>

<section class="section menu-section" id="menu-favorit">
    <div class="section-heading">
        <div>
            <p class="eyebrow">PILIHAN HARI INI</p>
            <h2>Menu Favorit</h2>
        </div>
        <a class="text-link desktop-link" href="<?= BASE_URL ?>/produk.php">Lihat semua menu <span aria-hidden="true">&#8599;</span></a>
    </div>
    <?php if ($featuredProducts): ?>
        <div class="product-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <?php require __DIR__ . '/includes/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="empty-state">Menu sedang disiapkan. Kunjungi kami lagi sebentar lagi.</p>
    <?php endif; ?>
    <a class="button button-outline mobile-link" href="<?= BASE_URL ?>/produk.php">Lihat semua menu <span aria-hidden="true">&#8599;</span></a>
</section>

<section class="story-band">
    <div class="story-image">
        <img src="<?= escape_html(website_image_url($websiteContent['about_image'] ?? '')) ?>" alt="<?= escape_html($websiteContent['about_title'] ?? 'Tentang NayaJi Cafe') ?>" loading="lazy">
    </div>
    <div class="story-copy">
        <p class="eyebrow">SEDIKIT TENTANG KAMI</p>
        <h2><?= nl2br(escape_html($websiteContent['about_title'] ?? 'Tentang NayaJi Cafe')) ?></h2>
        <p><?= escape_html($websiteContent['about_description'] ?? '') ?></p>
        <a class="text-link" href="<?= BASE_URL ?>/tentang.php">Cerita NayaJi <span aria-hidden="true">&#8599;</span></a>
    </div>
</section>

<section class="cta-band">
    <div>
        <p class="eyebrow">MEJA KAMI SELALU TERBUKA</p>
        <h2>Hari ini, mampir sebentar?</h2>
    </div>
    <a class="button button-light" href="<?= escape_html(BASE_URL . ($websiteContent['hero_cta_url'] ?? '/produk.php')) ?>"><?= escape_html($websiteContent['hero_cta_label'] ?? 'Pilih teman ngopi') ?> <span aria-hidden="true">&#8599;</span></a>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>