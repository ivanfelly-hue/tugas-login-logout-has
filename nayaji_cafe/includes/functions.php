<?php

function escape_html(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function rupiah($amount): string
{
    return 'Rp ' . number_format((float) $amount, 0, ',', '.');
}

function product_image_url(?string $image): string
{
    $image = trim((string) $image);

    if ($image !== '' && filter_var($image, FILTER_VALIDATE_URL)) {
        $host = parse_url($image, PHP_URL_HOST);
        if (parse_url($image, PHP_URL_SCHEME) === 'https' && $host === 'images.unsplash.com') {
            return $image;
        }
    }

    if ($image !== '' && basename($image) === $image) {
        return BASE_URL . '/uploads/produk/' . rawurlencode($image);
    }

    return 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1000&q=85';
}

function website_image_url(?string $image): string
{
    $image = trim((string) $image);

    if ($image !== '' && filter_var($image, FILTER_VALIDATE_URL)) {
        $host = parse_url($image, PHP_URL_HOST);
        if (parse_url($image, PHP_URL_SCHEME) === 'https' && $host === 'images.unsplash.com') {
            return $image;
        }
    }

    if ($image !== '' && basename($image) === $image) {
        return BASE_URL . '/uploads/website/' . rawurlencode($image);
    }

    return 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1200&q=85';
}

function render_product_card(array $product): void
{
    ?>
    <article class="product-card">
        <a class="product-photo" href="<?= BASE_URL ?>/detail.php?id=<?= (int) $product['id'] ?>">
            <img src="<?= escape_html(product_image_url($product['gambar'] ?? '')) ?>"
                 alt="<?= escape_html($product['nama']) ?>" loading="lazy">
            <span class="product-category"><?= escape_html($product['kategori']) ?></span>
        </a>
        <div class="product-info">
            <div class="product-title-row">
                <h3><a href="<?= BASE_URL ?>/detail.php?id=<?= (int) $product['id'] ?>"><?= escape_html($product['nama']) ?></a></h3>
                <span class="product-price"><?= rupiah($product['harga']) ?></span>
            </div>
            <p><?= escape_html(mb_strimwidth($product['deskripsi'], 0, 104, '...', 'UTF-8')) ?></p>
            <a class="text-link" href="<?= BASE_URL ?>/detail.php?id=<?= (int) $product['id'] ?>">Lihat detail <span aria-hidden="true">&#8599;</span></a>
        </div>
    </article>
    <?php
}