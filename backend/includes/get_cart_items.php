<?php
require_once __DIR__ . '/db_connection.php';

$cart_items = array();
$total = 0;

if (!empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $placeholders = rtrim(str_repeat('?,', count($product_ids)), ',');
    
    $stmt = $mysqli->prepare("SELECT id, name, price, image_url FROM products WHERE id IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($product_ids)), ...$product_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($product = $result->fetch_assoc()) {
        $quantity = $_SESSION['cart'][$product['id']];
        $subtotal = $product['price'] * $quantity;
        $total += $subtotal;
        $cart_items[] = $product + ['quantity' => $quantity, 'subtotal' => $subtotal];
    }
}

// Display cart items and total calculation