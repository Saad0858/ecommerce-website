<?php
require_once __DIR__ . '/includes/config.php';

session_start();

// Destroy all session data
$_SESSION = array();
session_destroy();

// Redirect to homepage
header("Location: " . BASE_URL);
exit;