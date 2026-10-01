<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Menu - NayaJi Cafe';
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
$categories = $pdo->query('SELECT DISTINCT kategori FROM produk ORDER BY kategori')->fetchAll(PDO::FETCH_COLUMN);
$countStatement = $pdo->prepare('SELECT COUNT(*) FROM produk' . $whereSql);
foreach ($parameters as $key => $value) {
    $countStatement->bindValue(':' . $key, $value, PDO::PARAM_STR);
}
$countStatement->execute();
$totalProducts = (int) $countStatement->fetchColumn();
$totalPages = max(1, (int) ceil($totalProducts / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$productStatement = $pdo->prepare(
    'SELECT id, nama, kategori, deskripsi, harga, stok, gambar, created_at FROM produk' .
    $whereSql . ' ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset'
);
foreach ($parameters as $key => $value) {
    $productStatement->bindValue(':' . $key, $value, PDO::PARAM_STR);
}
$productStatement->bindValue(':limit', $perPage, PDO::PARAM_INT);
$productStatement->bindValue(':offset', $offset, PDO::PARAM_INT);
$productStatement->execute();
$products = $productStatement->fetchAll();
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <p class="eyebrow">DARI BAR NAYAJI</p>
    <h1>Menu yang menemani<br><em>setiap suasana.</em></h1>
    <p>Racikan kopi, minuman segar, dan kudapan yang dibuat untuk dinikmati pelan-pelan.</p>
</section>

<section class="section catalog-section">
    <form class="catalog-tools" method="GET" action="<?= BASE_URL ?>/produk.php">
        <label class="search-field">
            <span class="sr-only">Cari nama produk</span>
            <input type="search" name="q" value="<?= escape_html($search) ?>" placeholder="Cari menu...">
        </label>
        <label class="filter-field">
            <span class="sr-only">Filter kategori</span>
            <select name="kategori">
                <option value="">Semua kategori</option>
                <?php foreach ($categories as $categoryOption): ?>
                    <option value="<?= escape_html($categoryOption) ?>" <?= $category === $categoryOption ? 'selected' : '' ?>><?= escape_html($categoryOption) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button class="button button-primary" type="submit">Terapkan</button>
        <?php if ($search !== '' || $category !== ''): ?>
            <a class="clear-link" href="<?= BASE_URL ?>/produk.php">Hapus filter</a>
        <?php endif; ?>
    </form>

    <div class="catalog-summary">
        <span><?= number_format($totalProducts, 0, ',', '.') ?> menu ditemukan</span>
        <?php if ($category !== ''): ?><span class="active-filter"><?= escape_html($category) ?></span><?php endif; ?>
    </div>
    <?php if ($products): ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <?php require __DIR__ . '/includes/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
        <?php if ($totalPages > 1): ?>
            <?php $queryBase = ['q' => $search, 'kategori' => $category]; ?>
            <nav class="pagination" aria-label="Halaman menu">
                <?php if ($page > 1): ?>
                    <a href="<?= BASE_URL ?>/produk.php?<?= http_build_query($queryBase + ['halaman' => $page - 1]) ?>" aria-label="Halaman sebelumnya">&larr;</a>
                <?php endif; ?>
                <span>Halaman <?= $page ?> dari <?= $totalPages ?></span>
                <?php if ($page < $totalPages): ?>
                    <a href="<?= BASE_URL ?>/produk.php?<?= http_build_query($queryBase + ['halaman' => $page + 1]) ?>" aria-label="Halaman berikutnya">&rarr;</a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state"><strong>Menu belum ditemukan.</strong><span>Coba kata kunci atau kategori lain.</span></div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>