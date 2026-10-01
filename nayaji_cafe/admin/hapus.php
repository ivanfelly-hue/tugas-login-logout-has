<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$productId = filter_var($_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['id'] ?? null) : ($_GET['id'] ?? null), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$productId) {
    set_flash('Produk tidak valid.', 'error');
    header('Location: ' . BASE_URL . '/admin/produk.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
}

$statement = $pdo->prepare('SELECT nama, gambar FROM produk WHERE id = :id');
$statement->execute(['id' => $productId]);
$product = $statement->fetch();
if (!$product) {
    set_flash('Produk tidak ditemukan.', 'error');
    header('Location: ' . BASE_URL . '/admin/produk.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $pageTitle = 'Konfirmasi Hapus Produk - NayaJi Cafe';
    require __DIR__ . '/../includes/header.php';
    require __DIR__ . '/../includes/admin_nav.php';
    ?>
    <section class="admin-page delete-confirm-page">
        <p class="eyebrow">KONFIRMASI</p>
        <h1>Apakah Anda yakin ingin menghapus produk ini?</h1>
        <div class="delete-confirm-product">
            <img src="<?= escape_html(product_image_url($product['gambar'])) ?>" alt="">
            <strong><?= escape_html($product['nama']) ?></strong>
        </div>
        <p>Data produk dan foto yang tersimpan di server akan dihapus.</p>
        <form method="POST" action="<?= BASE_URL ?>/admin/hapus.php">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) $productId ?>">
            <button class="button button-danger" type="submit">Ya, hapus produk</button>
            <a class="button button-quiet" href="<?= BASE_URL ?>/admin/produk.php">Batal</a>
        </form>
    </section>
    <?php
    require __DIR__ . '/../includes/footer.php';
    exit();
}

$deleteStatement = $pdo->prepare('DELETE FROM produk WHERE id = :id');
$deleteStatement->execute(['id' => $productId]);
if (!empty($product['gambar']) && basename($product['gambar']) === $product['gambar']) {
    $imagePath = __DIR__ . '/../uploads/produk/' . $product['gambar'];
    if (is_file($imagePath)) {
        unlink($imagePath);
    }
}
set_flash('Produk berhasil dihapus.', 'success');
header('Location: ' . BASE_URL . '/admin/produk.php');
exit();