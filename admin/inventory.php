<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/db_connection.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';

require_admin();

// Handle stock updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $new_stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
    
    if ($product_id && $new_stock !== false) {
        $stmt = $mysqli->prepare("UPDATE products SET stock = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_stock, $product_id);
        if ($stmt->execute()) {
            $success_message = "Inventory updated successfully";
        } else {
            $error_message = "Failed to update inventory";
        }
    }
}

// Get low stock products (less than 10 items)
$low_stock_stmt = $mysqli->prepare("SELECT * FROM products WHERE stock < 10 ORDER BY stock ASC");
$low_stock_stmt->execute();
$low_stock_result = $low_stock_stmt->get_result();
$low_stock_products = $low_stock_result->fetch_all(MYSQLI_ASSOC);

// Get all products for inventory management
$stmt = $mysqli->prepare("SELECT * FROM products ORDER BY category, name");
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory Management</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>
    
    <div class="container">
        <h1>Inventory Management</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>
        
        <div class="dashboard-section">
            <h2>Low Stock Alert</h2>
            <?php if (count($low_stock_products) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($low_stock_products as $product): ?>
                            <tr class="<?= $product['stock'] <= 5 ? 'critical-stock' : '' ?>">
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['category']) ?></td>
                                <td><?= $product['stock'] ?></td>
                                <td>
                                    <a href="product_edit.php?id=<?= $product['id'] ?>" class="btn btn-sm">Update</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No products with low stock.</p>
            <?php endif; ?>
        </div>
        
        <div class="dashboard-section">
            <h2>All Products Inventory</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Current Stock</th>
                        <th>Update Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['category']) ?></td>
                            <td><?= $product['stock'] ?></td>
                            <td>
                                <form method="POST" class="inline-form">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="number" name="stock" value="<?= $product['stock'] ?>" min="0" class="small-input">
                                    <button type="submit" name="update_stock" class="btn btn-sm">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php include 'admin_footer.php'; ?>
</body>
</html>