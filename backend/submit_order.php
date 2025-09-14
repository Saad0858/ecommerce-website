<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/get_cart_items.php';

// Validate and sanitize input data
$full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
$card_number = str_replace(' ', '', $_POST['card_number']);

// Get promo code if applied
$promo_code_id = $_SESSION['promo_code'] ?? null;
$discount = 0;

if ($promo_code_id) {
    $stmt = $mysqli->prepare("SELECT * FROM promo_codes WHERE id = ?");
    $stmt->bind_param("i", $promo_code_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($promo = $result->fetch_assoc()) {
        $discount = $total * ($promo['discount_percent'] / 100);
        $total = $total - $discount;
    }
}

// Start transaction
$mysqli->begin_transaction();
try {
    // Check inventory before processing
    foreach ($cart_items as $item) {
        $check_stmt = $mysqli->prepare("SELECT stock FROM products WHERE id = ?");
        $check_stmt->bind_param("i", $item['id']);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $product = $check_result->fetch_assoc();
        
        if ($product['stock'] < $item['quantity']) {
            throw new Exception("Not enough stock for {$item['name']}. Available: {$product['stock']}");
        }
    }
    
    // Insert order
    $stmt = $mysqli->prepare("INSERT INTO orders (user_id, total_amount, shipping_name, promo_code_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idsi", $_SESSION['user_id'], $total, $full_name, $promo_code_id);
    $stmt->execute();
    $order_id = $mysqli->insert_id;

    // Insert order items and update inventory
    foreach ($cart_items as $item) {
        // Insert order item
        $item_stmt = $mysqli->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $item_stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
        $item_stmt->execute();

        // Update product stock
        $update_stmt = $mysqli->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $update_stmt->bind_param("ii", $item['quantity'], $item['id']);
        $update_stmt->execute();
    }
    
    // Update promo code usage count if used
    if ($promo_code_id) {
        $promo_stmt = $mysqli->prepare("UPDATE promo_codes SET usage_count = usage_count + 1 WHERE id = ?");
        $promo_stmt->bind_param("i", $promo_code_id);
        $promo_stmt->execute();
        unset($_SESSION['promo_code']);
    }

    $mysqli->commit();
    unset($_SESSION['cart']);
    header("Location: " . BASE_URL . "/order_success.php?id=" . $order_id);
} catch (Exception $e) {
    $mysqli->rollback();
    $_SESSION['error'] = "Order processing failed: " . $e->getMessage();
    header("Location: " . BASE_URL . "/checkout.php");
}