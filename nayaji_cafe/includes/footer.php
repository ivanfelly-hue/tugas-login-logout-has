<?php
$footerContent = isset($websiteContent) ? $websiteContent : $pdo->query('SELECT whatsapp, email, instagram, alamat, jam_buka FROM website_content WHERE id = 1')->fetch();
$footerWhatsapp = preg_replace('/\D+/', '', $footerContent['whatsapp'] ?? '');
?>
</main>
<footer class="site-footer" id="kontak">
    <div class="footer-inner">
        <div>
            <a class="wordmark footer-wordmark" href="<?= BASE_URL ?>/">NayaJi <span>Cafe</span></a>
            <p><?= escape_html($footerContent['alamat'] ?? 'Ruang kecil untuk kopi baik dan cerita yang hangat.') ?></p>
        </div>
        <div class="footer-links">
            <a href="<?= BASE_URL ?>/produk.php">Menu</a>
            <a href="<?= BASE_URL ?>/tentang.php">Tentang kami</a>
            <?php if (!empty($footerContent['email'])): ?><a href="mailto:<?= escape_html($footerContent['email']) ?>"><?= escape_html($footerContent['email']) ?></a><?php endif; ?>
            <?php if ($footerWhatsapp !== ''): ?><a href="https://wa.me/<?= escape_html($footerWhatsapp) ?>" target="_blank" rel="noopener">WhatsApp <?= escape_html($footerContent['whatsapp']) ?></a><?php endif; ?>
            <?php if (!empty($footerContent['instagram'])): ?><a href="https://www.instagram.com/<?= rawurlencode(ltrim($footerContent['instagram'], '@')) ?>/" target="_blank" rel="noopener"><?= escape_html($footerContent['instagram']) ?></a><?php endif; ?>
            <?php if (!empty($footerContent['jam_buka'])): ?><span>Jam buka: <?= escape_html($footerContent['jam_buka']) ?></span><?php endif; ?>
        </div>
        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> NayaJi Cafe</span>
            <a href="<?= BASE_URL ?>/admin/login.php">Admin</a>
        </div>
    </div>
</footer>
</body>
</html>