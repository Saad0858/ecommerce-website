<?php
require_once __DIR__ . '/../../backend/includes/auth_check.php';
require_once __DIR__ . '/../../backend/includes/config.php';
require_once __DIR__ . '/../../backend/includes/db_connection.php';

$order = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderNumber = $_POST['order_number'];
    $email = $_POST['email'];
    
    $stmt = $mysqli->prepare("SELECT * FROM orders WHERE order_number = ? AND email = ?");
    $stmt->bind_param("ss", $orderNumber, $email);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
}
?>
<!-- HTML form and order display -->