<?php
require_once __DIR__ . '/../../backend/includes/auth_check.php';
require_once __DIR__ . '/../../backend/includes/config.php';
require_once __DIR__ . '/../../backend/includes/db_connection.php';

$order = null;
if (isset($_GET['id'])) {
    $orderId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($orderId) {
        $stmt = $mysqli->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Tracking - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .fade-in{animation:fadeIn .6s ease-in-out}@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .tracking-summary { background: #f8f9fa; padding: 20px; border-radius: 10px; }
        .tracking-summary h3 { margin-top: 0; }
        .tracking-summary p { margin-bottom: 10px; }
        .btn-primary { background-color: #0d6efd; border-color: #0d6efd; }
        .btn-primary:hover { background-color: #0b5ed7; border-color: #0b5ed7; }
        .btn-danger { background-color: #dc3545; border-color: #dc3545; }
        .btn-danger:hover { background-color: #a71d2a; border-color: #a71d2a; }
    </style>
</head>
<body>
    <?php include __DIR__ . "/../public/includes/header.php"; ?>
    
    <div class="container py-4 fade-in">
        <h1 class="page-title">Order Tracking</h1>
        
        <?php if (isset($_SESSION['cart_message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['cart_message'] ?></div>
            <?php unset($_SESSION['cart_message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if ($order): ?>
            <div class="tracking-summary">
                <h3>Order Summary</h3>
                <p><strong>Order Number:</strong> <?= htmlspecialchars($order['order_number']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
                <p><strong>Total Amount:</strong> ₹<?= number_format($order['total_amount'], 2) ?></p>
                <p><strong>Order Date:</strong> <?= date('M d, Y', strtotime($order['created_at'])) ?></p>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                No order found with the provided ID.
            </div>
        <?php endif; ?>
    </div>
    
    <?php include __DIR__ . "/../public/includes/footer.php"; ?>
</body>
</html>