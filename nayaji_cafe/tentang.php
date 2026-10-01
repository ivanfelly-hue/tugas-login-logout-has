<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
$websiteContent = $pdo->query('SELECT * FROM website_content WHERE id = 1')->fetch();
$pageTitle = 'Tentang Kami - NayaJi Cafe';
require __DIR__ . '/includes/header.php';
?>
<section class="about-hero">
    <div class="about-copy">
        <p class="eyebrow">CERITA NAYAJI</p>
        <h1><?= nl2br(escape_html($websiteContent['about_title'] ?? 'Tentang NayaJi Cafe')) ?></h1>
        <p><?= escape_html($websiteContent['about_description'] ?? '') ?></p>
    </div>
    <img src="<?= escape_html(website_image_url($websiteContent['about_image'] ?? '')) ?>" alt="<?= escape_html($websiteContent['about_title'] ?? 'NayaJi Cafe') ?>">
</section>
<section class="section about-values">
    <p class="eyebrow">CARA KAMI MENYAMBUT</p>
    <h2>Hal kecil, dibuat dengan sungguh-sungguh.</h2>
    <div class="value-list">
        <article><span>01</span><h3>Diracik segar</h3><p>Setiap minuman dibuat saat dipesan, dengan bahan yang dipilih untuk rasa yang konsisten.</p></article>
        <article><span>02</span><h3>Ruang untuk semua</h3><p>Datang sendiri, bekerja sebentar, atau berbagi cerita. Selalu ada tempat untukmu.</p></article>
        <article><span>03</span><h3>Lebih dari secangkir</h3><p>Kami ingin setiap kunjungan menjadi bagian kecil yang baik dari harimu.</p></article>
    </div>
</section>
<section class="cta-band">
    <div><p class="eyebrow">SENANG BERTEMU DENGANMU</p><h2>Yuk, pilih teman ngopi.</h2></div>
    <a class="button button-light" href="<?= BASE_URL ?>/produk.php">Lihat menu <span aria-hidden="true">&#8599;</span></a>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>