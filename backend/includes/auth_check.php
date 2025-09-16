<?php
// backend/includes/auth_check.php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';   // guarantees BASE_URL is defined

function require_admin(): void
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }

    // case-insensitive role check
    if (strcasecmp((string)($_SESSION['role'] ?? ''), 'admin') !== 0) {
        $_SESSION['error'] = 'Admin access required';
        header('Location: ' . BASE_URL . '/account.php');
        exit;
    }

    // final gate
    if (empty($_SESSION['is_admin'])) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}