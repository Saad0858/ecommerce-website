<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/auth_check.php';

require_admin();

$order_id   = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
$new_status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

$allowed_statuses = ['pending', 'processing', 'completed', 'cancelled'];

if ($order_id && in_array($new_status, $allowed_statuses)) {
    $stmt = $mysqli->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['success'] = "Order #$order_id updated to $new_status.";
    } else {
        $_SESSION['error'] = "Order update failed or no change made.";
    }
} else {
    $_SESSION['error'] = "Invalid input.";
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
