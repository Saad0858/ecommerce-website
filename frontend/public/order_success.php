<?php
require_once __DIR__ . '/../../backend/includes/config.php';
require_once __DIR__ . '/../../backend/includes/auth_check.php';
require_once __DIR__ . '/../../backend/includes/db_connection.php';

// Get order ID from URL
$order_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$order_id) {
    header("Location: account.php");
    exit;
}

// Fetch order
$stmt = $mysqli->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    header("Location: account.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Success - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . "/../public/includes/header.php"; ?>
    
    <div class="container">
        <h1>🎉 Order Confirmed</h1>
        <p>Thank you for your purchase, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>!</p>
        
        <div class="order-card">
            <p><strong>Order Number:</strong> <?= htmlspecialchars($order['order_number']) ?></p>
            <p><strong>Total:</strong> ₹<?= number_format($order['total_amount'], 2) ?></p>
            <p><strong>Status:</strong> <?= ucfirst($order['status']) ?></p>
            <p><strong>Placed On:</strong> <?= date("F j, Y, g:i a", strtotime($order['created_at'])) ?></p>
        </div>
        
        <a href="account.php" class="btn">Go to My Account</a>
        <a href="products.php" class="btn btn-sm">Continue Shopping</a>
    </div>
    
    <?php include __DIR__ . "/../public/includes/footer.php"; ?>
</body>
</html>
