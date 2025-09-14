<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/auth_check.php';

require_admin();

$order_id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
$new_status = filter_input(INPUT_POST, 'status');

$allowed_statuses = ['pending', 'processing', 'completed', 'cancelled'];

if ($order_id && in_array($new_status, $allowed_statuses)) {
    $stmt = $mysqli->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $updateSuccess = $stmt->affected_rows;
    
    if ($updateSuccess) {
        // Send notification
        $orderEmail = $orderDetails['email'];
        send_order_notification($orderEmail, $orderNumber, $newStatus);
        
        $_SESSION['success'] = "Order status updated successfully.";
    } else {
        $_SESSION['error'] = "Error updating order status.";
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);