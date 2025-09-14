<?php
require_once __DIR__ . '/../../backend/includes/config.php';
require_once __DIR__ . '/../../backend/includes/db_connection.php';
require_once __DIR__ . '/../../backend/includes/auth_check.php';

$order_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$order_id) {
    header("Location: account.php");
    exit;
}

// Get order details
$stmt = $mysqli->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: account.php");
    exit;
}

$order = $result->fetch_assoc();

// Get order items
$items_stmt = $mysqli->prepare("SELECT oi.*, p.name, p.image_url 
                              FROM order_items oi 
                              JOIN products p ON oi.product_id = p.id 
                              WHERE oi.order_id = ?");
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
$order_items = $items_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . "/../public/includes/header.php"; ?>
    
    <div class="container">
        <div class="breadcrumb">
            <a href="account.php">My Account</a> &gt; Order #<?= $order_id ?>
        </div>
        
        <h1>Order Details</h1>
        
        <div class="order-info-grid">
            <div class="order-summary">
                <h3>Order Summary</h3>
                <table class="info-table">
                    <tr>
                        <th>Order Number:</th>
                        <td>#<?= $order_id ?></td>
                    </tr>
                    <tr>
                        <th>Date Placed:</th>
                        <td><?= date('F j, Y', strtotime($order['created_at'])) ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td><span class="status-badge <?= strtolower($order['status']) ?>"><?= $order['status'] ?></span></td>
                    </tr>
                    <tr>
                        <th>Total Amount:</th>
                        <td>$<?= number_format($order['total_amount'], 2) ?></td>
                    </tr>
                </table>
                
                <div class="order-actions">
                    <a href="order_tracking.php?id=<?= $order_id ?>" class="btn">Track Order</a>
                </div>
            </div>
        </div>
        
        <div class="order-items">
            <h3>Order Items</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <?php if ($item['image_url']): ?>
                                        <img src="<?= $item['image_url'] ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="thumbnail">
                                    <?php endif; ?>
                                    <div>
                                        <a href="product.php?id=<?= $item['product_id'] ?>"><?= htmlspecialchars($item['name']) ?></a>
                                    </div>
                                </div>
                            </td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <td>$<?= number_format($order['total_amount'], 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <?php include __DIR__ . "/../public/includes/footer.php"; ?>
</body>
</html>