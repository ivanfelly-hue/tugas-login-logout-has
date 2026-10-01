<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/konten.php');
    exit();
}
verify_csrf();

$fields = [
    'hero_title' => trim((string) ($_POST['hero_title'] ?? '')),
    'hero_subtitle' => trim((string) ($_POST['hero_subtitle'] ?? '')),
    'hero_description' => trim((string) ($_POST['hero_description'] ?? '')),
    'hero_cta_label' => trim((string) ($_POST['hero_cta_label'] ?? '')),
    'hero_cta_url' => trim((string) ($_POST['hero_cta_url'] ?? '')),
    'about_title' => trim((string) ($_POST['about_title'] ?? '')),
    'about_description' => trim((string) ($_POST['about_description'] ?? '')),
    'whatsapp' => trim((string) ($_POST['whatsapp'] ?? '')),
    'email' => trim((string) ($_POST['email'] ?? '')),
    'instagram' => trim((string) ($_POST['instagram'] ?? '')),
    'alamat' => trim((string) ($_POST['alamat'] ?? '')),
    'jam_buka' => trim((string) ($_POST['jam_buka'] ?? '')),
];
$limits = [
    'hero_title' => 180, 'hero_subtitle' => 180, 'hero_description' => 5000,
    'hero_cta_label' => 60, 'hero_cta_url' => 255, 'about_title' => 180,
    'about_description' => 5000, 'whatsapp' => 30, 'email' => 150,
    'instagram' => 100, 'alamat' => 255, 'jam_buka' => 120,
];
$errors = [];
foreach ($limits as $field => $limit) {
    if (strlen($fields[$field]) > $limit) {
        $errors[] = 'Panjang salah satu input melebihi batas.';
        break;
    }
}
foreach (['hero_title', 'hero_subtitle', 'hero_description', 'hero_cta_label', 'hero_cta_url', 'about_title', 'about_description'] as $requiredField) {
    if ($fields[$requiredField] === '') {
        $errors[] = 'Judul, deskripsi, dan tombol utama wajib diisi.';
        break;
    }
}
if (!preg_match('~^/(?!/)[A-Za-z0-9_./?=&%#-]*$~', $fields['hero_cta_url'])) {
    $errors[] = 'Tujuan tombol harus berupa path website internal yang diawali /.';
}
if ($fields['whatsapp'] !== '' && !preg_match('/^[0-9+()\s-]+$/', $fields['whatsapp'])) {
    $errors[] = 'Nomor WhatsApp hanya boleh berisi angka, spasi, tanda +, kurung, atau tanda hubung.';
}
if ($fields['email'] !== '' && !filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Alamat email tidak valid.';
}
if ($fields['instagram'] !== '' && !preg_match('/^@?[A-Za-z0-9._]+$/', $fields['instagram'])) {
    $errors[] = 'Nama Instagram tidak valid.';
}

$currentContent = $pdo->query('SELECT hero_image, about_image FROM website_content WHERE id = 1')->fetch();
if (!$currentContent) {
    set_flash('Konten website belum tersedia. Jalankan database.sql terlebih dahulu.', 'error');
    header('Location: ' . BASE_URL . '/admin/konten.php');
    exit();
}
$imageFields = ['hero_image', 'about_image'];
$uploadExtensions = [];
foreach ($imageFields as $imageField) {
    $file = $_FILES[$imageField] ?? null;
    if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        continue;
    }
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 2 * 1024 * 1024 || !is_uploaded_file($file['tmp_name'] ?? '')) {
        $errors[] = 'Foto hero/About gagal divalidasi atau ukurannya melebihi 2 MB.';
        continue;
    }
    $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
    if (!isset($extensions[$mimeType]) || @getimagesize($file['tmp_name']) === false) {
        $errors[] = 'Foto website harus berupa JPG, JPEG, atau PNG yang valid.';
        continue;
    }
    $uploadExtensions[$imageField] = $extensions[$mimeType];
}

if ($errors) {
    set_flash(implode(' ', array_unique($errors)), 'error');
    header('Location: ' . BASE_URL . '/admin/konten.php');
    exit();
}

$uploadDirectory = __DIR__ . '/../uploads/website';
if ($uploadExtensions && !is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
    set_flash('Folder uploads/website tidak dapat dibuat.', 'error');
    header('Location: ' . BASE_URL . '/admin/konten.php');
    exit();
}
if ($uploadExtensions && !is_writable($uploadDirectory)) {
    set_flash('Folder uploads/website tidak memiliki izin tulis.', 'error');
    header('Location: ' . BASE_URL . '/admin/konten.php');
    exit();
}

$newImages = [];
foreach ($uploadExtensions as $imageField => $extension) {
    $file = $_FILES[$imageField];
    $fileName = bin2hex(random_bytes(16)) . '.' . $extension;
    if (!move_uploaded_file($file['tmp_name'], $uploadDirectory . '/' . $fileName)) {
        foreach ($newImages as $newImage) {
            if (is_file($uploadDirectory . '/' . $newImage)) {
                unlink($uploadDirectory . '/' . $newImage);
            }
        }
        set_flash('Foto website tidak dapat disimpan.', 'error');
        header('Location: ' . BASE_URL . '/admin/konten.php');
        exit();
    }
    $newImages[$imageField] = $fileName;
}

$fields['hero_image'] = $newImages['hero_image'] ?? $currentContent['hero_image'];
$fields['about_image'] = $newImages['about_image'] ?? $currentContent['about_image'];
try {
    $pdo->beginTransaction();
    $statement = $pdo->prepare('UPDATE website_content SET hero_title = :hero_title, hero_subtitle = :hero_subtitle, hero_description = :hero_description, hero_image = :hero_image, hero_cta_label = :hero_cta_label, hero_cta_url = :hero_cta_url, about_title = :about_title, about_description = :about_description, about_image = :about_image, whatsapp = :whatsapp, email = :email, instagram = :instagram, alamat = :alamat, jam_buka = :jam_buka WHERE id = 1');
    $statement->execute($fields);
    $pdo->commit();
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    foreach ($newImages as $newImage) {
        if (is_file($uploadDirectory . '/' . $newImage)) {
            unlink($uploadDirectory . '/' . $newImage);
        }
    }
    set_flash('Konten website gagal disimpan. Silakan coba lagi.', 'error');
    header('Location: ' . BASE_URL . '/admin/konten.php');
    exit();
}

foreach ($newImages as $imageField => $newImage) {
    $oldImage = $currentContent[$imageField];
    if ($oldImage !== '' && basename($oldImage) === $oldImage && is_file($uploadDirectory . '/' . $oldImage)) {
        unlink($uploadDirectory . '/' . $oldImage);
    }
}
set_flash('Konten website berhasil diperbarui.', 'success');
header('Location: ' . BASE_URL . '/admin/konten.php');
exit();