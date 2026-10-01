<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Siapkan Admin - NayaJi Cafe';
$errors = [];
$adminExists = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn() > 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$adminExists) {
    verify_csrf();
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirmation = (string) ($_POST['konfirmasi_password'] ?? '');

    if ($username === '' || strlen($username) > 50) {
        $errors[] = 'Username wajib diisi dan maksimal 50 karakter.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password minimal 8 karakter.';
    }
    if ($password !== $confirmation) {
        $errors[] = 'Konfirmasi password tidak sama.';
    }

    if (!$errors) {
        try {
            $statement = $pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (:username, :password_hash)');
            $statement->execute([
                'username' => $username,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ]);
            set_flash('Akun admin berhasil dibuat. Silakan login.', 'success');
            header('Location: ' . BASE_URL . '/admin/login.php');
            exit();
        } catch (PDOException $exception) {
            $errors[] = 'Username sudah digunakan atau akun gagal dibuat.';
        }
    }
}

require __DIR__ . '/../includes/header.php';
?>
<section class="auth-layout">
    <div class="auth-panel">
        <p class="eyebrow">PENGATURAN PERTAMA</p>
        <h1>Buat akun admin</h1>
        <?php if ($adminExists): ?>
            <p>Akun admin sudah tersedia. Setup hanya dapat digunakan satu kali.</p>
            <a class="button button-primary" href="<?= BASE_URL ?>/admin/login.php">Ke halaman login</a>
        <?php else: ?>
            <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= escape_html($error) ?></div><?php endforeach; ?>
            <form class="admin-form" method="POST">
                <?= csrf_field() ?>
                <label class="form-field"><span>Username</span><input type="text" name="username" maxlength="50" autocomplete="username" required></label>
                <label class="form-field"><span>Password (minimal 8 karakter)</span><input type="password" name="password" autocomplete="new-password" required></label>
                <label class="form-field"><span>Ulangi password</span><input type="password" name="konfirmasi_password" autocomplete="new-password" required></label>
                <button class="button button-primary" type="submit">Buat akun admin</button>
            </form>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>