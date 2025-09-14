<?php
session_start();

// Add role checking
function require_admin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . BASE_URL . "/login.php");
        exit;
    }
    
    if ($_SESSION['role'] !== 'admin') {
        $_SESSION['error'] = "Admin access required";
        header("Location: " . BASE_URL . "/account.php");
        exit;
    }
}