<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$productId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$product = null;
if ($productId) {
    $statement = $pdo->prepare('SELECT id, nama, kategori, deskripsi, harga, stok, gambar FROM produk WHERE id = :id');
    $statement->execute(['id' => $productId]);
    $product = $statement->fetch();
}

if (!$product) {
    http_response_code(404);
}
$pageTitle = $product ? escape_html($product['nama']) . ' - NayaJi Cafe' : 'Menu tidak ditemukan - NayaJi Cafe';
require __DIR__ . '/includes/header.php';
?>
<section class="section detail-section">
    <?php if ($product): ?>
        <a class="back-link" href="<?= BASE_URL ?>/produk.php">&larr; Kembali ke menu</a>
        <div class="product-detail">
            <div class="detail-photo"><img src="<?= escape_html(product_image_url($product['gambar'])) ?>" alt="<?= escape_html($product['nama']) ?>"></div>
            <div class="detail-copy">
                <p class="eyebrow"><?= escape_html($product['kategori']) ?></p>
                <h1><?= escape_html($product['nama']) ?></h1>
                <p class="detail-price"><?= rupiah($product['harga']) ?></p>
                <p class="detail-description"><?= nl2br(escape_html($product['deskripsi'])) ?></p>
                <p class="stock-note"><?= (int) $product['stok'] > 0 ? 'Tersedia hari ini' : 'Stok sedang habis' ?></p>
                <a class="button button-primary" href="<?= BASE_URL ?>/produk.php">Jelajahi menu lain <span aria-hidden="true">&#8599;</span></a>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-state"><strong>Menu tidak ditemukan.</strong><a class="text-link" href="<?= BASE_URL ?>/produk.php">Kembali ke katalog</a></div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>