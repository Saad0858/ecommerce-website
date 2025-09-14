<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/init_cart.php';
require_once __DIR__ . '/includes/db_connection.php';

$product_id = filter_var($_POST['product_id'], FILTER_VALIDATE_INT);
$quantity = max(1, filter_var($_POST['quantity'], FILTER_VALIDATE_INT));

// Check product availability
$stmt = $mysqli->prepare("SELECT id, price, stock FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($row['stock'] >= $quantity) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        $_SESSION['cart_message'] = "Product added to cart!";
    } else {
        $_SESSION['error'] = "Insufficient stock available";
    }
}

header("Location: " . BASE_URL . "/product.php?id=" . $product_id);
exit;