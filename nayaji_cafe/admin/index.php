<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pageTitle = 'Dashboard Admin - NayaJi Cafe';
$productCount = (int) $pdo->query('SELECT COUNT(*) FROM produk')->fetchColumn();
$stockCount = (int) $pdo->query('SELECT COALESCE(SUM(stok), 0) FROM produk')->fetchColumn();
$categoryCount = (int) $pdo->query('SELECT COUNT(DISTINCT kategori) FROM produk')->fetchColumn();
$recentProducts = $pdo->query('SELECT id, nama, kategori, harga, stok FROM produk ORDER BY created_at DESC, id DESC LIMIT 5')->fetchAll();
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/admin_nav.php';
?>
<section class="admin-page">
    <div class="admin-heading">
        <div><p class="eyebrow">RINGKASAN</p><h1>Selamat datang, <?= escape_html($_SESSION['admin_username'] ?? 'Admin') ?></h1></div>
        <a class="button button-primary" href="<?= BASE_URL ?>/admin/tambah.php">+ Tambah produk</a>
    </div>
    <div class="stats-grid">
        <article class="stat-block"><span>Total produk</span><strong><?= number_format($productCount, 0, ',', '.') ?></strong><small>Menu terdaftar</small></article>
        <article class="stat-block"><span>Jumlah stok</span><strong><?= number_format($stockCount, 0, ',', '.') ?></strong><small>Semua produk</small></article>
        <article class="stat-block"><span>Kategori</span><strong><?= number_format($categoryCount, 0, ',', '.') ?></strong><small>Kategori menu aktif</small></article>
    </div>
    <div class="admin-section-heading"><div><p class="eyebrow">TERBARU</p><h2>Produk terakhir ditambahkan</h2></div><a class="text-link" href="<?= BASE_URL ?>/admin/produk.php">Kelola semua <span aria-hidden="true">&#8599;</span></a></div>
    <div class="table-wrap">
        <table class="admin-table">
            <thead><tr><th>Nama produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($recentProducts as $product): ?>
                <tr><td><strong><?= escape_html($product['nama']) ?></strong></td><td><?= escape_html($product['kategori']) ?></td><td><?= rupiah($product['harga']) ?></td><td><?= (int) $product['stok'] ?></td><td><a href="<?= BASE_URL ?>/admin/edit.php?id=<?= (int) $product['id'] ?>">Edit</a></td></tr>
            <?php endforeach; ?>
            <?php if (!$recentProducts): ?><tr><td colspan="5">Belum ada produk. Tambahkan produk pertama.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>