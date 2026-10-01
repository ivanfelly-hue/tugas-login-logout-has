<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pageTitle = 'Tambah Produk - NayaJi Cafe';
$product = ['nama' => '', 'kategori' => '', 'deskripsi' => '', 'harga' => '', 'stok' => 0, 'gambar' => '', 'featured' => 0];
$action = 'create';
$submitLabel = 'Simpan produk';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/admin_nav.php';
?>
<section class="admin-page admin-form-page">
    <a class="back-link" href="<?= BASE_URL ?>/admin/produk.php">&larr; Kembali ke produk</a>
    <div class="admin-heading"><div><p class="eyebrow">PRODUK BARU</p><h1>Tambah produk</h1><p>Detail menu yang ditampilkan di katalog NayaJi.</p></div></div>
    <?php require __DIR__ . '/../includes/form_produk.php'; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>