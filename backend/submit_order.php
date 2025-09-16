<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db_connection.php';

// Build cart items + calculate total
$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $product_ids = array_map('intval', array_keys($_SESSION['cart']));
    if ($product_ids) {
        $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
        $sql = "SELECT id, name, price FROM products WHERE id IN ($placeholders)";
        $stmt = $mysqli->prepare($sql);
        $types = str_repeat('i', count($product_ids));
        $stmt->bind_param($types, ...$product_ids);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($product = $result->fetch_assoc()) {
            $quantity = (int)$_SESSION['cart'][$product['id']];
            $subtotal = $product['price'] * $quantity;
            $total += $subtotal;

            $cart_items[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }
}

if (empty($cart_items)) {
    $_SESSION['error'] = "Your cart is empty.";
    header("Location: " . BASE_URL . "/cart.php");
    exit;
}

// Validate and sanitize input data
$full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
$postal_code = filter_input(INPUT_POST, 'postal_code', FILTER_SANITIZE_STRING);
$country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);

// Promo code
$promo_code_id = $_SESSION['promo_code'] ?? null;
$discount = 0;
if ($promo_code_id) {
    $stmt = $mysqli->prepare("SELECT * FROM promo_codes WHERE id = ?");
    $stmt->bind_param("i", $promo_code_id);
    $stmt->execute();
    $promo = $stmt->get_result()->fetch_assoc();
    if ($promo) {
        $discount = $total * ($promo['discount_percent'] / 100);
        $total -= $discount;
    }
}

$mysqli->begin_transaction();
try {
    // Check inventory
    foreach ($cart_items as $item) {
        $check_stmt = $mysqli->prepare("SELECT stock FROM products WHERE id = ?");
        $check_stmt->bind_param("i", $item['id']);
        $check_stmt->execute();
        $stock = $check_stmt->get_result()->fetch_assoc();
        if ($stock['stock'] < $item['quantity']) {
            throw new Exception("Not enough stock for {$item['name']}");
        }
    }

    // Insert order
    $order_number = strtoupper(uniqid('ORD'));
    $user_id = $_SESSION['user_id'] ?? null;

    $stmt = $mysqli->prepare("INSERT INTO orders 
        (user_id, total_amount, shipping_name, promo_code_id, order_number, email, address, city, postal_code, country) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "idssisssss",
        $user_id,
        $total,
        $full_name,
        $promo_code_id,
        $order_number,
        $email,
        $address,
        $city,
        $postal_code,
        $country
    );
    $stmt->execute();
    $order_id = $mysqli->insert_id;

    // Insert order items
    foreach ($cart_items as $item) {
        $item_stmt = $mysqli->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $item_stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
        $item_stmt->execute();

        // Update stock
        $update_stmt = $mysqli->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $update_stmt->bind_param("ii", $item['quantity'], $item['id']);
        $update_stmt->execute();
    }

    if ($promo_code_id) {
        $promo_stmt = $mysqli->prepare("UPDATE promo_codes SET usage_count = usage_count + 1 WHERE id = ?");
        $promo_stmt->bind_param("i", $promo_code_id);
        $promo_stmt->execute();
        unset($_SESSION['promo_code']);
    }

    $mysqli->commit();
    unset($_SESSION['cart']);
    header("Location: " . BASE_URL . "/order_success.php?id=" . $order_id);
    exit;

} catch (Exception $e) {
    $mysqli->rollback();
    $_SESSION['error'] = "Order failed: " . $e->getMessage();
    header("Location: " . BASE_URL . "/checkout.php");
    exit;
}
