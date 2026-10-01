<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/produk.php');
    exit();
}
verify_csrf();

$action = (string) ($_POST['aksi'] ?? '');
$productId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$nama = trim((string) ($_POST['nama'] ?? ''));
$kategori = trim((string) ($_POST['kategori'] ?? ''));
$deskripsi = trim((string) ($_POST['deskripsi'] ?? ''));
$hargaInput = trim((string) ($_POST['harga'] ?? ''));
$stokInput = trim((string) ($_POST['stok'] ?? ''));
$featured = isset($_POST['featured']) ? 1 : 0;
$errors = [];
$existingProduct = null;

if (!in_array($action, ['create', 'update'], true) || ($action === 'update' && !$productId)) {
    set_flash('Permintaan produk tidak valid.', 'error');
    header('Location: ' . BASE_URL . '/admin/produk.php');
    exit();
}
if ($action === 'update') {
    $findStatement = $pdo->prepare('SELECT id, gambar FROM produk WHERE id = :id');
    $findStatement->execute(['id' => $productId]);
    $existingProduct = $findStatement->fetch();
    if (!$existingProduct) {
        set_flash('Produk yang akan diedit tidak ditemukan.', 'error');
        header('Location: ' . BASE_URL . '/admin/produk.php');
        exit();
    }
}

if (empty($nama) || strlen($nama) > 100) {
    $errors[] = 'Nama produk wajib diisi dan maksimal 100 karakter.';
}
if (empty($kategori) || strlen($kategori) > 50) {
    $errors[] = 'Kategori wajib diisi dan maksimal 50 karakter.';
}
if (empty($deskripsi) || strlen($deskripsi) > 3000) {
    $errors[] = 'Deskripsi wajib diisi dan maksimal 3000 karakter.';
}
if (!is_numeric($hargaInput) || !preg_match('/^\d+(?:\.\d{1,2})?$/', $hargaInput) || (float) $hargaInput > 9999999999.99) {
    $errors[] = 'Harga harus angka positif dengan maksimal 2 angka desimal.';
}
if (!is_numeric($stokInput) || !preg_match('/^\d+$/', $stokInput) || (float) $stokInput > 2147483647) {
    $errors[] = 'Stok harus berupa bilangan bulat positif.';
}

$file = $_FILES['gambar'] ?? null;
$hasUpload = is_array($file) && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
$mimeType = '';
$extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
if ($hasUpload) {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $errors[] = 'Foto gagal diunggah. Periksa ukuran file dan coba lagi.';
    } elseif (($file['size'] ?? 0) > 2 * 1024 * 1024) {
        $errors[] = 'Ukuran foto maksimal 2 MB.';
    } elseif (!is_uploaded_file($file['tmp_name'] ?? '')) {
        $errors[] = 'File foto tidak valid.';
    } else {
        $fileInfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $fileInfo->file($file['tmp_name']);
        if (!isset($extensions[$mimeType]) || @getimagesize($file['tmp_name']) === false) {
            $errors[] = 'Format foto harus JPG, JPEG, atau PNG yang valid.';
        }
    }
} elseif ($action === 'create') {
    $errors[] = 'Foto produk wajib diunggah.';
}

if ($errors) {
    set_flash(implode(' ', $errors), 'error');
    $destination = $action === 'update'
        ? BASE_URL . '/admin/edit.php?id=' . (int) $productId
        : BASE_URL . '/admin/tambah.php';
    header('Location: ' . $destination);
    exit();
}

$imageName = $existingProduct['gambar'] ?? '';
$newImageName = '';
if ($hasUpload) {
    $uploadDirectory = __DIR__ . '/../uploads/produk';
    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
        set_flash('Folder upload tidak dapat dibuat.', 'error');
        header('Location: ' . BASE_URL . '/admin/' . ($action === 'update' ? 'edit.php?id=' . (int) $productId : 'tambah.php'));
        exit();
    }
    if (!is_writable($uploadDirectory)) {
        set_flash('Folder uploads/produk tidak memiliki izin tulis.', 'error');
        header('Location: ' . BASE_URL . '/admin/' . ($action === 'update' ? 'edit.php?id=' . (int) $productId : 'tambah.php'));
        exit();
    }
    $newImageName = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
    if (!move_uploaded_file($file['tmp_name'], $uploadDirectory . '/' . $newImageName)) {
        set_flash('Foto tidak dapat disimpan ke folder upload.', 'error');
        header('Location: ' . BASE_URL . '/admin/' . ($action === 'update' ? 'edit.php?id=' . (int) $productId : 'tambah.php'));
        exit();
    }
    $imageName = $newImageName;
}

try {
    if ($action === 'create') {
        $statement = $pdo->prepare('INSERT INTO produk (nama, kategori, deskripsi, harga, stok, gambar, featured) VALUES (:nama, :kategori, :deskripsi, :harga, :stok, :gambar, :featured)');
    } else {
        $statement = $pdo->prepare('UPDATE produk SET nama = :nama, kategori = :kategori, deskripsi = :deskripsi, harga = :harga, stok = :stok, gambar = :gambar, featured = :featured WHERE id = :id');
    }
    $values = [
        'nama' => $nama,
        'kategori' => $kategori,
        'deskripsi' => $deskripsi,
        'harga' => $hargaInput,
        'stok' => (int) $stokInput,
        'gambar' => $imageName,
        'featured' => $featured,
    ];
    if ($action === 'update') {
        $values['id'] = (int) $productId;
    }
    $statement->execute($values);
} catch (Throwable $exception) {
    if ($newImageName !== '' && is_file(__DIR__ . '/../uploads/produk/' . $newImageName)) {
        unlink(__DIR__ . '/../uploads/produk/' . $newImageName);
    }
    set_flash('Produk gagal disimpan. Periksa kembali data dan koneksi database.', 'error');
    header('Location: ' . BASE_URL . '/admin/' . ($action === 'update' ? 'edit.php?id=' . (int) $productId : 'tambah.php'));
    exit();
}

if ($newImageName !== '' && !empty($existingProduct['gambar']) && basename($existingProduct['gambar']) === $existingProduct['gambar']) {
    $oldImagePath = __DIR__ . '/../uploads/produk/' . $existingProduct['gambar'];
    if (is_file($oldImagePath)) {
        unlink($oldImagePath);
    }
}
set_flash($action === 'create' ? 'Produk berhasil ditambahkan.' : 'Produk berhasil diperbarui.', 'success');
header('Location: ' . BASE_URL . '/admin/produk.php');
exit();