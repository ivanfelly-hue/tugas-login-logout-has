<form class="admin-form" method="POST" action="<?= BASE_URL ?>/admin/proses_produk.php" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="aksi" value="<?= escape_html($action) ?>">
    <?php if (!empty($product['id'])): ?>
        <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
    <?php endif; ?>
    <div class="form-grid">
        <label class="form-field">
            <span>Nama produk</span>
            <input type="text" name="nama" maxlength="100" value="<?= escape_html($product['nama'] ?? '') ?>" required>
        </label>
        <label class="form-field">
            <span>Kategori</span>
            <input type="text" name="kategori" maxlength="50" value="<?= escape_html($product['kategori'] ?? '') ?>" list="kategori-list" required>
            <datalist id="kategori-list"><option value="Kopi"><option value="Non-kopi"><option value="Pastry"></datalist>
        </label>
        <label class="form-field form-field-wide">
            <span>Deskripsi</span>
            <textarea name="deskripsi" rows="5" maxlength="3000" required><?= escape_html($product['deskripsi'] ?? '') ?></textarea>
        </label>
        <label class="form-field">
            <span>Harga (rupiah)</span>
            <input type="number" name="harga" min="0" max="9999999999.99" step="0.01" value="<?= escape_html((string) ($product['harga'] ?? '')) ?>" required>
        </label>
        <label class="form-field">
            <span>Stok</span>
            <input type="number" name="stok" min="0" step="1" value="<?= escape_html((string) ($product['stok'] ?? 0)) ?>" required>
        </label>
        <label class="form-field form-field-wide">
            <span>Foto produk (JPG, JPEG, PNG, maks. 2 MB)<?= $action === 'create' ? '' : ' - opsional jika tidak diganti' ?></span>
            <input type="file" name="gambar" accept="image/jpeg,image/png" <?= $action === 'create' ? 'required' : '' ?>>
        </label>
        <label class="featured-toggle form-field-wide">
            <input type="checkbox" name="featured" value="1" <?= !empty($product['featured']) ? 'checked' : '' ?>>
            <span>Tampilkan di Menu Favorit</span>
        </label>
    </div>
    <?php if (!empty($product['gambar'])): ?>
        <div class="current-image">
            <img src="<?= escape_html(product_image_url($product['gambar'])) ?>" alt="Foto produk saat ini">
            <span>Foto saat ini</span>
        </div>
    <?php endif; ?>
    <div class="form-actions">
        <button class="button button-primary" type="submit"><?= escape_html($submitLabel) ?></button>
        <a class="button button-quiet" href="<?= BASE_URL ?>/admin/produk.php">Batal</a>
    </div>
</form>