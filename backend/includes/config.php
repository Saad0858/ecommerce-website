<?php
// backend/includes/config.php



define("BASE_URL", "http://localhost/ECommerce_Project/frontend/public");
define("FRONTEND_PATH", __DIR__ . "/../../frontend/");

// Site configuration
define('SITE_NAME', 'EyeStore');
// Change this line
// define('BASE_URL', 'http://localhost:8000/frontend/public');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security settings
define('ENVIRONMENT', 'development'); // Change to 'production' when live

function send_order_notification($email, $orderNumber, $status) {
    $subject = "Order #$orderNumber Update";
    $message = "Your order status has been updated to: $status";
    $headers = "From: noreply@amanaecom.com";
    
    return mail($email, $subject, $message, $headers);
}

// Stripe Payment Integration Setup
define('STRIPE_SECRET_KEY', 'sk_test_...');
define('STRIPE_PUBLIC_KEY', 'pk_test_...');
?>