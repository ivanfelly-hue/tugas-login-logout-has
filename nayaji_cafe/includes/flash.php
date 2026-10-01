<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function set_flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => in_array($type, ['success', 'error'], true) ? $type : 'success',
    ];
}

function display_flash(): void
{
    if (empty($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $type = in_array($flash['type'] ?? '', ['success', 'error'], true) ? $flash['type'] : 'success';
    $message = htmlspecialchars((string) ($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8');

    echo '<div class="alert alert-' . $type . '" role="status">' . $message . '</div>';
}