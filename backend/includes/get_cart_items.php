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
            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            <div class="cart-item-details">
                <h5><?= htmlspecialchars($product['name']) ?></h5>
                <p>$<?= number_format($product['price'], 2) ?> × <?= $quantity ?></p>
                <p><strong>Subtotal:</strong> $<?= number_format($subtotal, 2) ?></p>
            </div>
            <div class="cart-item-actions">
                <form method="post" action="cart_update.php" style="margin:0;">
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                    <input type="number" name="quantity" value="<?= $quantity ?>" min="1" onchange="this.form.submit()">
                </form>
                <a href="cart.php?action=remove&id=<?= $product['id'] ?>" class="remove-btn">Remove</a>
            </div>
        </div>
        <?php
    }
} else {
    echo "<p>Your cart is empty.</p>";
}
?>

<script>
    // Update total dynamically on page load
    document.getElementById("cart-total").textContent = "<?= number_format($total, 2) ?>";
</script>
