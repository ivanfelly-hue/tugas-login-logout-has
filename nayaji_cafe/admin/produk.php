<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pageTitle = 'Kelola Produk - NayaJi Cafe';
$search = trim((string) ($_GET['q'] ?? ''));
$category = trim((string) ($_GET['kategori'] ?? ''));
$page = filter_var($_GET['halaman'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;
$perPage = 10;
$conditions = [];
$parameters = [];
if ($search !== '') {
    $conditions[] = 'nama LIKE :search';
    $parameters['search'] = '%' . $search . '%';
}
if ($category !== '') {
    $conditions[] = 'kategori = :kategori';
    $parameters['kategori'] = $category;
}
$whereSql = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';
$categories = array_values(array_unique(array_merge(
    ['Coffee', 'Tea', 'Non Coffee', 'Dessert'],
    $pdo->query('SELECT DISTINCT kategori FROM produk ORDER BY kategori')->fetchAll(PDO::FETCH_COLUMN)
)));
$countStatement = $pdo->prepare('SELECT COUNT(*) FROM produk' . $whereSql);
foreach ($parameters as $key => $value) {
    $countStatement->bindValue(':' . $key, $value, PDO::PARAM_STR);
}
$countStatement->execute();
$totalProducts = (int) $countStatement->fetchColumn();
$totalPages = max(1, (int) ceil($totalProducts / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;
$statement = $pdo->prepare('SELECT id, nama, kategori, deskripsi, harga, stok, gambar, featured, created_at FROM produk' . $whereSql . ' ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset');
foreach ($parameters as $key => $value) {
    $statement->bindValue(':' . $key, $value, PDO::PARAM_STR);
}
$statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
$statement->bindValue(':offset', $offset, PDO::PARAM_INT);
$statement->execute();
$products = $statement->fetchAll();

require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/admin_nav.php';
?>
<section class="admin-page">
    <div class="admin-heading">
        <div><p class="eyebrow">KATALOG</p><h1>Kelola produk</h1></div>
        <a class="button button-primary" href="<?= BASE_URL ?>/admin/tambah.php">+ Tambah produk</a>
    </div>
    <form class="catalog-tools admin-tools" method="GET">
        <label class="search-field"><span class="sr-only">Cari nama produk</span><input type="search" name="q" value="<?= escape_html($search) ?>" placeholder="Cari produk..."></label>
        <label class="filter-field" id="filter-kategori"><span class="sr-only">Filter kategori</span><select name="kategori"><option value="">Semua Kategori</option><?php foreach ($categories as $categoryOption): ?><option value="<?= escape_html($categoryOption) ?>" <?= $category === $categoryOption ? 'selected' : '' ?>><?= escape_html($categoryOption) ?></option><?php endforeach; ?></select></label>
        <button class="button button-primary" type="submit">Cari</button>
        <?php if ($search !== '' || $category !== ''): ?><a class="clear-link" href="<?= BASE_URL ?>/admin/produk.php">Reset</a><?php endif; ?>
    </form>
    <div class="catalog-summary"><span><?= number_format($totalProducts, 0, ',', '.') ?> produk</span></div>
    <div class="table-wrap">
        <table class="admin-table">
            <thead><tr><th>Produk</th><th>Kategori</th><th>Deskripsi</th><th>Harga</th><th>Stok</th><th>Dibuat</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><div class="table-product"><img src="<?= escape_html(product_image_url($product['gambar'])) ?>" alt="" loading="lazy"><strong><?= escape_html($product['nama']) ?><?= !empty($product['featured']) ? ' <span class="featured-badge">Favorit</span>' : '' ?></strong></div></td>
                    <td><?= escape_html($product['kategori']) ?></td>
                    <td class="admin-description"><?= escape_html(mb_strimwidth($product['deskripsi'], 0, 72, '...', 'UTF-8')) ?></td>
                    <td><?= rupiah($product['harga']) ?></td>
                    <td><?= (int) $product['stok'] ?></td>
                    <td><?= escape_html(date('d M Y', strtotime($product['created_at']))) ?></td>
                    <td><div class="row-actions"><a href="<?= BASE_URL ?>/admin/edit.php?id=<?= (int) $product['id'] ?>">Edit</a><a class="delete-link" href="<?= BASE_URL ?>/admin/hapus.php?id=<?= (int) $product['id'] ?>">Hapus</a></div></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$products): ?><tr><td colspan="7">Tidak ada produk yang cocok.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($totalPages > 1): ?>
        <?php $queryBase = ['q' => $search, 'kategori' => $category]; ?>
        <nav class="pagination" aria-label="Halaman produk admin">
            <?php if ($page > 1): ?><a class="pagination-word" href="?<?= http_build_query($queryBase + ['halaman' => $page - 1]) ?>">Previous</a><?php endif; ?>
            <?php for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++): ?>
                <a class="<?= $pageNumber === $page ? 'current-page' : '' ?>" href="?<?= http_build_query($queryBase + ['halaman' => $pageNumber]) ?>" <?= $pageNumber === $page ? 'aria-current="page"' : '' ?>><?= $pageNumber ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?><a class="pagination-word" href="?<?= http_build_query($queryBase + ['halaman' => $page + 1]) ?>">Next</a><?php endif; ?>
        </nav>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>