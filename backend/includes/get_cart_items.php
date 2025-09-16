<?php
require_once __DIR__ . '/db_connection.php';

$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $product_ids = array_map('intval', array_keys($_SESSION['cart'])); // force ints
    $id_list = implode(',', $product_ids); // safe because we cast to int

    $sql = "SELECT id, name, price, image_url FROM products WHERE id IN ($id_list)";
    $result = $mysqli->query($sql);

    while ($product = $result->fetch_assoc()) {
        $quantity = (int)$_SESSION['cart'][$product['id']];
        $subtotal = $product['price'] * $quantity;
        $total += $subtotal;

        // Save for later use if needed
        $cart_items[] = $product + [
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];

        // Output HTML for each item
        ?>
        <div class="cart-item">
            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="cart-item-image">
            <div class="cart-item-details">
                <h5 class="item-name"><?= htmlspecialchars($product['name']) ?></h5>
                <p class="item-price">$<?= number_format($product['price'], 2) ?></p>
                <p class="item-subtotal"><strong>Subtotal:</strong> $<?= number_format($subtotal, 2) ?></p>
            </div>
            <div class="cart-item-actions">
                <input type="number" name="quantity" value="<?= $quantity ?>" min="1" 
                       onchange="updateQuantity(this, <?= $product['id'] ?>)" 
                       onkeypress="handleQuantityKeyPress(event, this, <?= $product['id'] ?>)">
                <a href="cart.php?action=remove&id=<?= $product['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove this item?')">Remove</a>
            </div>
        </div>
        <?php
    }
} else {
    echo "<p class='empty-cart'>Your cart is empty.</p>";
}
?>