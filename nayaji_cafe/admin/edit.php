<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$productId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$statement = $pdo->prepare('SELECT id, nama, kategori, deskripsi, harga, stok, gambar, featured FROM produk WHERE id = :id');
$statement->execute(['id' => $productId ?: 0]);
$product = $statement->fetch();
if (!$product) {
    set_flash('Produk tidak ditemukan.', 'error');
    header('Location: ' . BASE_URL . '/admin/produk.php');
    exit();
}

$pageTitle = 'Edit Produk - NayaJi Cafe';
$action = 'update';
$submitLabel = 'Simpan perubahan';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/admin_nav.php';
?>
<section class="admin-page admin-form-page">
    <a class="back-link" href="<?= BASE_URL ?>/admin/produk.php">&larr; Kembali ke produk</a>
    <div class="admin-heading"><div><p class="eyebrow">PERBARUI KATALOG</p><h1>Edit produk</h1><p><?= escape_html($product['nama']) ?></p></div></div>
    <?php require __DIR__ . '/../includes/form_produk.php'; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>