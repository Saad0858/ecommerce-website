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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .breadcrumb {
            margin-bottom: 20px;
            font-size: 14px;
            color: #6c757d;
        }
        
        .breadcrumb a {
            color: #4e73df;
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-weight: 700;
            border-bottom: 2px solid #4e73df;
            padding-bottom: 10px;
        }
        
        .order-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .order-summary, .shipping-info {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .order-summary h3, .shipping-info h3 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-table th, .info-table td {
            padding: 12px 0;
            border-bottom: 1px solid #f8f9fa;
            text-align: left;
        }
        
        .info-table th {
            font-weight: 600;
            color: #495057;
            width: 180px;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-badge.pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-badge.processing {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .status-badge.completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-badge.cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .order-actions {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .btn {
            display: inline-block;
            background-color: #4e73df;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #2e59d9;
        }
        
        .order-items {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .order-items h3 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th, .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        
        .table tfoot th {
            background-color: transparent;
            text-align: right;
            border-top: 2px solid #e9ecef;
        }
        
        .product-cell {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .product-cell a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
        }
        
        .product-cell a:hover {
            color: #4e73df;
        }
        
        @media (max-width: 768px) {
            .order-info-grid {
                grid-template-columns: 1fr;
            }
            
            .table {
                display: block;
                overflow-x: auto;
            }
            
            .product-cell {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .thumbnail {
                width: 50px;
                height: 50px;
            }
        }
    </style>
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
                        <td>₹<?= number_format($order['total_amount'], 2) ?></td>
                    </tr>
                </table>
                
                <div class="order-actions">
                    <a href="order_tracking.php?id=<?= $order_id ?>" class="btn btn-primary">Track Order</a>
                </div>
            </div>
            
            <div class="shipping-info">
                <h3>Shipping Information</h3>
                <table class="info-table">
                    <tr>
                        <th>Shipping Address:</th>
                        <td><?= htmlspecialchars($order['address']) ?></td>
                    </tr>
                    <tr>
                        <th>Shipping Method:</th>
                        <td><?= htmlspecialchars($order['shipping_method'] ?? 'Self Pickup') ?></td>
                    </tr>
                    <tr>
                        <th>Payment Method:</th>
                        <td><?= htmlspecialchars($order['payment_method'] ?? 'Cash on Delivery') ?></td>
                    </tr>
                    <tr>
                        <th>Tracking Number:</th>
                        <td><?= htmlspecialchars($order['tracking_number'] ?? 'Not available yet') ?></td>
                    </tr>
                </table>
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
                            <td>₹<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" style="text-align: right;">Total</th>
                        <td><strong>₹<?= number_format($order['total_amount'], 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <?php include __DIR__ . "/../public/includes/footer.php"; ?>
</body>
</html>