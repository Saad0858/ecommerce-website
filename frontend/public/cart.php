<?php
// frontend/public/cart.php
require_once __DIR__ . '/../../backend/includes/config.php';
require_once __DIR__ . '/../../backend/includes/db_connection.php';

// ensure session started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// init cart (if you have extra logic)
if (file_exists(__DIR__ . '/../../backend/includes/init_cart.php')) {
    require_once __DIR__ . '/../../backend/includes/init_cart.php';
}

// helper to send JSON and stop execution
function send_json($data, $http_code = 200) {
    http_response_code($http_code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

/* -------------------------------------------------------------
   AJAX: Update quantity (POST)
   ------------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    // read inputs
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity   = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

    if ($product_id <= 0 || $quantity <= 0) {
        send_json(['success' => false, 'error' => 'Invalid product ID or quantity.']);
    }

    // Ensure cart exists
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Update session cart
    $_SESSION['cart'][$product_id] = $quantity;

    // Fetch price of the updated product
    $stmt = $mysqli->prepare("SELECT price FROM products WHERE id = ?");
    if (!$stmt) {
        send_json(['success' => false, 'error' => 'Database prepare failed.'], 500);
    }
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if (!$res) {
        send_json(['success' => false, 'error' => 'Failed to fetch product price.'], 500);
    }
    $prod = $res->fetch_assoc();
    if (!$prod) {
        send_json(['success' => false, 'error' => 'Product not found in database.']);
    }
    $subtotal = $prod['price'] * $quantity;
    $stmt->close();

    // Calculate new cart total in one query (if cart not empty)
    $total = 0.00;
    $ids = [];
    foreach ($_SESSION['cart'] as $id => $qty) {
        $ids[] = (int)$id;
    }

    if (!empty($ids)) {
        // build safe id list
        $id_list = implode(',', $ids);
        $sql = "SELECT id, price FROM products WHERE id IN ($id_list)";
        $result = $mysqli->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $id = (int)$row['id'];
                $qty = isset($_SESSION['cart'][$id]) ? (int)$_SESSION['cart'][$id] : 0;
                $total += $row['price'] * $qty;
            }
        } else {
            // If this fails, return a safe message
            send_json(['success' => false, 'error' => 'Failed to calculate cart total.'], 500);
        }
    }

    // Return JSON success response (strings are fine for display)
    send_json([
        'success' => true,
        'subtotal' => number_format($subtotal, 2),
        'total' => number_format($total, 2),
    ]);
}

/* -------------------------------------------------------------
   GET handlers (add / remove) and page render follow
   ------------------------------------------------------------- */

// Handle adding a product to the cart
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'add') {
    $product_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($product_id) {
        $stmt = $mysqli->prepare("SELECT id, name, price, stock FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($product = $result->fetch_assoc()) {
            if ($product['stock'] > 0) {
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id] += 1;
                } else {
                    $_SESSION['cart'][$product_id] = 1;
                }
                $_SESSION['cart_message'] = "Product added to cart!";
            } else {
                $_SESSION['error'] = "This product is out of stock.";
            }
        } else {
            $_SESSION['error'] = "Product not found.";
        }
    } else {
        $_SESSION['error'] = "Invalid product ID.";
    }
    header("Location: " . BASE_URL . "/products.php");
    exit;
}

// Handle removing a product from the cart
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'remove') {
    $product_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($product_id && isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        $_SESSION['cart_message'] = "Product removed from cart.";
    } else {
        $_SESSION['error'] = "Invalid product ID.";
    }
    header("Location: " . BASE_URL . "/cart.php");
    exit;
}

// Calculate total for initial page load
$total = 0;
if (!empty($_SESSION['cart'])) {
    $product_ids = array_map('intval', array_keys($_SESSION['cart']));
    if (!empty($product_ids)) {
        $id_list = implode(',', $product_ids);

        $sql = "SELECT id, price FROM products WHERE id IN ($id_list)";
        $result = $mysqli->query($sql);

        if ($result) {
            while ($product = $result->fetch_assoc()) {
                $quantity = (int)$_SESSION['cart'][$product['id']];
                $total += $product['price'] * $quantity;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
        // Function to update quantity via AJAX
        function updateQuantity(input, productId) {
            var quantity = parseInt(input.value) || 1;
            if (quantity < 1) {
                input.value = 1;
                quantity = 1;
            }

            // store original value if not present
            if (!input.getAttribute('data-original-value')) {
                input.setAttribute('data-original-value', input.defaultValue || quantity);
            }

            var formData = new FormData();
            formData.append('action', 'update');
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            fetch('cart.php', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => {
                // try to parse JSON even if status isn't 200; server returns proper JSON on errors
                return response.json().then(data => ({ ok: response.ok, status: response.status, data }));
            })
            .then(({ ok, status, data }) => {
                console.log('Response data:', data);
                if (data && data.success) {
                    document.getElementById('cart-total').textContent = data.total;
                    var productElement = input.closest('.cart-item');
                    var subtotalElement = productElement.querySelector('.item-subtotal');
                    if (subtotalElement) {
                        subtotalElement.textContent = 'Subtotal: ₹' + data.subtotal;
                    }
                    showNotification('Quantity updated successfully!', 'success');
                    input.setAttribute('data-original-value', quantity);
                } else {
                    showNotification('Error: ' + (data.error || 'Unknown error'), 'error');
                    // Reset on error
                    input.value = input.getAttribute('data-original-value') || 1;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showNotification('Error updating quantity. Please try again.', 'error');
                input.value = input.getAttribute('data-original-value') || 1;
            });
        }

        function handleQuantityKeyPress(event, input, productId) {
            if (event.key === 'Enter') {
                updateQuantity(input, productId);
                event.preventDefault();
            }
        }

        // notification helper (unchanged)
        function showNotification(message, type) {
            const existing = document.getElementById('quantity-update-notification');
            if (existing) existing.remove();
            const n = document.createElement('div');
            n.id = 'quantity-update-notification';
            n.className = 'alert ' + (type === 'success' ? 'alert-success' : 'alert-danger');
            n.innerHTML = message + '<button type="button" class="close" onclick="this.parentElement.remove()">&times;</button>';
            n.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 1050; opacity: 0; transform: translateY(-10px); transition: all 0.3s ease; max-width: 350px;';
            document.body.appendChild(n);
            setTimeout(()=>{ n.style.opacity='1'; n.style.transform='translateY(0)'; }, 10);
            setTimeout(()=>{ if(n.parentElement){ n.style.opacity='0'; n.style.transform='translateY(-10px)'; setTimeout(()=>n.remove(),300); } }, 3000);
        }
    </script>
    <style>
        /* small alert styles for notifications */
        .alert { padding: 12px; margin-bottom: 12px; border-radius: 6px; }
        .alert-success { background:#dff0d8; color:#3c763d; border:1px solid #d6e9c6; }
        .alert-danger { background:#f2dede; color:#a94442; border:1px solid #ebccd1; }
        .close { float:right; font-size:1.1rem; background:transparent; border:0; cursor:pointer; }
    </style>
</head>
<body>
    <?php include __DIR__ . "/../public/includes/header.php"; ?>

    <div class="container">
        <h1 class="page-title">Your Shopping Cart</h1>

        <?php if (isset($_SESSION['cart_message'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['cart_message']) ?></div>
            <?php unset($_SESSION['cart_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="cart-wrapper">
            <div class="cart-items">
                <?php
                // include get_cart_items.php (which outputs cart item HTML)
                if (file_exists(__DIR__ . '/../../backend/includes/get_cart_items.php')) {
                    include __DIR__ . '/../../backend/includes/get_cart_items.php';
                } else {
                    echo "<p>Your cart is empty.</p>";
                }
                ?>
            </div>

            <div class="cart-summary">
                <h3>Order Summary</h3>
                <p><strong>Total:</strong> ₹<span id="cart-total"><?= number_format($total, 2) ?></span></p>
                <a href="checkout.php" class="btn btn-primary w-100">Proceed to Checkout</a>
            </div>
        </div>
    </div>

    <?php include __DIR__ . "/../public/includes/footer.php"; ?>
</body>
</html>
