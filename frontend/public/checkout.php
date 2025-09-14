<?php
require_once __DIR__ . '/../../backend/includes/config.php';
require_once __DIR__ . '/../../backend/includes/auth_check.php';
require_once __DIR__ . '/../../backend/includes/get_cart_items.php';

if (empty($cart_items)) {
    header("Location: cart.php");
    exit;
}

// Handle promo code application
$promo_code = null;
$discount = 0;

if (isset($_POST['apply_promo']) && !empty($_POST['promo_code'])) {
    $code = strtoupper(trim($_POST['promo_code']));
    
    // Check if promo code exists and is valid
    $stmt = $mysqli->prepare("SELECT * FROM promo_codes 
                            WHERE code = ? AND is_active = 1 
                            AND start_date <= CURDATE() AND end_date >= CURDATE() 
                            AND (usage_limit IS NULL OR usage_count < usage_limit)");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($promo = $result->fetch_assoc()) {
        // Check minimum order amount
        if ($total >= $promo['min_order_amount']) {
            $promo_code = $promo;
            $discount = $total * ($promo['discount_percent'] / 100);
            $total = $total - $discount;
            $_SESSION['promo_code'] = $promo['id'];
            $success_message = "Promo code applied successfully!";
        } else {
            $error_message = "This promo code requires a minimum order of $" . number_format($promo['min_order_amount'], 2);
        }
    } else {
        $error_message = "Invalid or expired promo code";
    }
}

// Remove promo code if requested
if (isset($_GET['remove_promo'])) {
    unset($_SESSION['promo_code']);
    header("Location: checkout.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Checkout</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>
        
        <div class="order-summary">
            <h3>Order Summary</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($promo_code): ?>
                        <tr>
                            <td colspan="3">Subtotal</td>
                            <td>$<?= number_format($total + $discount, 2) ?></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                Discount (<?= $promo_code['code'] ?> - <?= $promo_code['discount_percent'] ?>%)
                                <a href="?remove_promo=1" class="small-link">[Remove]</a>
                            </td>
                            <td>-$<?= number_format($discount, 2) ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td><strong>$<?= number_format($total, 2) ?></strong></td>
                    </tr>
                </tbody>
            </table>
            
            <?php if (!$promo_code): ?>
                <form method="POST" class="promo-form">
                    <div class="form-group">
                        <label>Promo Code:</label>
                        <input type="text" name="promo_code" placeholder="Enter promo code">
                        <button type="submit" name="apply_promo" class="btn btn-sm">Apply</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
        
        <div class="checkout-form">
            <form action="../../backend/submit_order.php" method="POST">
                <!-- Shipping Information -->
                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" name="full_name" required>
                </div>
                
                <!-- Payment Details -->
                <div class="payment-section">
                    <h3>Payment Information</h3>
                    <div id="card-element"></div>
                    <button id="submit-payment" class="btn">Pay $<?= number_format($total, 2) ?></button>
                </div>
                <script src="https://js.stripe.com/v3/"></script>
                
                <button type="submit" class="btn">Place Order</button>
            </form>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>