<?php
// Main entry point for the EyeStore application
require_once '../../backend/includes/config.php';

// Redirect to products page or another appropriate landing page
header("Location: " . BASE_URL . "/products.php");
exit;
?>