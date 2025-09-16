<?php
require_once __DIR__ . '/../../backend/includes/config.php';
require_once __DIR__ . '/../../backend/includes/db_connection.php';
require_once __DIR__ . '/../../backend/includes/auth_check.php';

// Get user details
$stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get user orders with current status
$order_stmt = $mysqli->prepare("SELECT o.*, 
                              (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count 
                              FROM orders o 
                              WHERE o.user_id = ? 
                              ORDER BY o.created_at DESC");
$order_stmt->bind_param("i", $_SESSION['user_id']);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
$orders = $order_result->fetch_all(MYSQLI_ASSOC);

// Handle notification preferences update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_preferences'])) {
    $notify_orders = isset($_POST['notify_orders']) ? 1 : 0;
    $notify_promos = isset($_POST['notify_promos']) ? 1 : 0;
    
    $update_stmt = $mysqli->prepare("UPDATE users SET notify_orders = ?, notify_promos = ? WHERE id = ?");
    $update_stmt->bind_param("iii", $notify_orders, $notify_promos, $_SESSION['user_id']);
    
    if ($update_stmt->execute()) {
        $success_message = "Preferences updated successfully";
        // Update user data
        $user['notify_orders'] = $notify_orders;
        $user['notify_promos'] = $notify_promos;
    } else {
        $error_message = "Error updating preferences";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . "/../public/includes/header.php"; ?>
    
    <div class="container">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>
        
        <div class="account-grid">
            <div class="account-sidebar">
                <div class="profile-card">
                    <h3>Account Information</h3>
                    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not provided') ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($user['address'] ?? 'Not provided') ?></p>
                    <p><strong>Account Type:</strong> <?= $user['role'] === 'admin' ? 'Administrator' : 'Customer' ?></p>
                    <a href="edit_profile.php" class="btn btn-sm">Edit Profile</a>
                    <a href="change_password.php" class="btn btn-sm">Change Password</a>
                </div>
                
                <div class="notification-settings">
                    <h3>Notification Preferences</h3>
                    <form method="post">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="notify_orders" <?= isset($user['notify_orders']) && $user['notify_orders'] ? 'checked' : '' ?>>
                                Receive order status updates
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="notify_promos" <?= isset($user['notify_promos']) && $user['notify_promos'] ? 'checked' : '' ?>>
                                Receive promotional emails
                            </label>
                        </div>
                        <button type="submit" name="update_preferences" class="btn btn-sm">Save Preferences</button>
                    </form>
                </div>
            </div>
            
            <div class="account-main">
                <div class="orders-section">
                    <h2>Order History</h2>
                    
                    <?php if (count($orders) > 0): ?>
                        <div class="order-list">
                            <?php foreach ($orders as $order): ?>
                                <div class="order-card">
                                    <div class="order-header">
                                        <div>
                                            <h4>Order #<?= $order['id'] ?></h4>
                                            <p class="order-date"><?= date('F j, Y', strtotime($order['created_at'])) ?></p>
                                        </div>
                                        <div class="order-status <?= strtolower($order['status']) ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="order-details">
                                        <p><strong>Items:</strong> <?= $order['item_count'] ?></p>
                                        <p><strong>Total:</strong> ₹<?= number_format($order['total_amount'], 2) ?></p>
                                    </div>
                                    
                                    <div class="order-actions">
                                        <a href="order_details.php?id=<?= $order['id'] ?>" class="btn btn-sm">View Details</a>
                                        <?php if ($order['status'] !== 'cancelled' && $order['status'] !== 'completed'): ?>
                                            <a href="order_tracking.php?id=<?= $order['id'] ?>" class="btn btn-sm">Track Order</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>You haven't placed any orders yet.</p>
                        <a href="products.php" class="btn">Start Shopping</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . "/../public/includes/footer.php"; ?>
</body>
</html>