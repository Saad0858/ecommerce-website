<?php
require_once __DIR__ . '/../../backend/includes/config.php';
require_once __DIR__ . '/../../backend/includes/auth_check.php';
?>
<!DOCTYPE html>
<html>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Your Shopping Cart</h1>
        <?php include '../../backend/includes/get_cart_items.php'; ?>
        
        <div class="cart-total">
            <p>Total: $<span id="cart-total">0.00</span></p>
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>