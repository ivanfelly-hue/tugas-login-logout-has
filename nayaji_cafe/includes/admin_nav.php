<div class="admin-bar">
    <div class="admin-bar-title"><span class="eyebrow">NAYAJI CAFE</span><strong>Panel pengelola</strong></div>
    <nav aria-label="Navigasi admin">
        <a href="<?= BASE_URL ?>/admin/index.php">Dashboard</a>
        <a href="<?= BASE_URL ?>/admin/produk.php">Produk</a>
        <a href="<?= BASE_URL ?>/admin/tambah.php">Tambah Produk</a>
        <a href="<?= BASE_URL ?>/admin/produk.php#filter-kategori">Kategori</a>
        <a href="<?= BASE_URL ?>/admin/konten.php">Konten Website</a>
        <a href="<?= BASE_URL ?>/admin/konten.php#kontak">Pengaturan</a>
        <a class="btn-website" href="<?= BASE_URL ?>/" target="_blank" rel="noopener">Lihat Website <span aria-hidden="true">&#8599;</span></a>
    </nav>
    <form method="POST" action="<?= BASE_URL ?>/admin/logout.php">
        <?= csrf_field() ?>
        <button class="admin-logout" type="submit">Logout</button>
    </form>
</div>