<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';
require_once __DIR__ . '/../backend/includes/get_products.php';

// Admin check...
?>
<div class="admin-container">
    <h2>Product Management</h2>
    <a href="product_add.php" class="btn">Add New Product</a>
    
    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product['id'] ?></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= number_format($product['price'], 2) ?></td>
            <td><?= $product['stock'] ?></td>
            <td>
                <a href="product_edit.php?id=<?= $product['id'] ?>">Edit</a>
                <a href="product_delete.php?id=<?= $product['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>