<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';
require_once __DIR__ . '/../includes/functions.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit();
}

$pageTitle = 'Login Admin - NayaJi Cafe';
$error = '';
$adminExists = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn() > 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $statement = $pdo->prepare('SELECT id, username, password_hash FROM admin_users WHERE username = :username LIMIT 1');
    $statement->execute(['username' => $username]);
    $admin = $statement->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        set_flash('Selamat datang, ' . $admin['username'] . '.', 'success');
        header('Location: ' . BASE_URL . '/admin/index.php');
        exit();
    }
    $error = 'Username atau password tidak sesuai.';
}

require __DIR__ . '/../includes/header.php';
?>
<section class="auth-layout">
    <div class="auth-panel">
        <p class="eyebrow">AREA PENGELOLA</p>
        <h1>Login admin</h1>
        <?php if ($error !== ''): ?><div class="alert alert-error"><?= escape_html($error) ?></div><?php endif; ?>
        <form class="admin-form" method="POST">
            <?= csrf_field() ?>
            <label class="form-field"><span>Username</span><input type="text" name="username" autocomplete="username" required></label>
            <label class="form-field"><span>Password</span><input type="password" name="password" autocomplete="current-password" required></label>
            <button class="button button-primary" type="submit">Masuk ke dashboard</button>
        </form>
        <a class="back-website" href="../index.php">&larr; Back to Website</a>
        <?php if (!$adminExists): ?><p class="setup-note">Belum ada admin? <a href="<?= BASE_URL ?>/admin/setup_admin.php">Buat akun pertama</a></p><?php endif; ?>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>