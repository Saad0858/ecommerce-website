<?php
require_once __DIR__ . '/../../backend/includes/config.php';
require_once __DIR__ . '/../../backend/includes/auth_check.php';
require_once __DIR__ . '/../../backend/includes/get_order.php';

if (!$order) {
    header("Location: account.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Order Confirmed (#<?= $order['id'] ?>)</h1>
        <p>Thank you for your purchase! Your order has been successfully processed.</p>
    </div>
</body>
</html>