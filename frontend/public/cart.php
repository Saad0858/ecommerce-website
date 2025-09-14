<?php
require_once __DIR__ . '/../../backend/includes/config.php';
require_once __DIR__ . '/../../backend/includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . "/../public/includes/header.php"; ?>
    
    <div class="container">
        <h1 class="page-title">Your Shopping Cart</h1>
        
        <div class="cart-wrapper">
            <div class="cart-items">
                <?php include '../../backend/includes/get_cart_items.php'; ?>
            </div>
            
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <p><strong>Total:</strong> $<span id="cart-total">0.00</span></p>
                <a href="checkout.php" class="btn btn-primary w-100">Proceed to Checkout</a>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . "/../public/includes/footer.php"; ?>
</body>
</html>
