<?php
$pageTitle = $pageTitle ?? 'NayaJi Cafe';
require_once __DIR__ . '/flash.php';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#7a5cff">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css">
</head>
<body>
<header class="site-header">
    <div class="header-inner">
        <a class="wordmark" href="<?= BASE_URL ?>/" aria-label="NayaJi Cafe, beranda">NayaJi <span>Cafe</span></a>
        <nav class="site-nav" aria-label="Navigasi utama">
            <a href="<?= BASE_URL ?>/#beranda">Beranda</a>
            <a href="<?= BASE_URL ?>/tentang.php">Tentang</a>
            <a href="<?= BASE_URL ?>/produk.php">Menu</a>
            <a href="<?= BASE_URL ?>/#kontak">Kontak</a>
        </nav>
        <a class="header-action" href="<?= BASE_URL ?>/produk.php">Jelajahi menu <span aria-hidden="true">&#8599;</span></a>
    </div>
</header>
<main>
    <div class="flash-wrap"><?php display_flash(); ?></div>