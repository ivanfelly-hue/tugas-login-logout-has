<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/flash.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit();
}

verify_csrf();
$_SESSION = [];
session_regenerate_id(true);
set_flash('Anda sudah keluar dari panel admin.', 'success');
header('Location: ' . BASE_URL . '/admin/login.php');
exit();