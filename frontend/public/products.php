<?php
require_once __DIR__ . '/../../backend/includes/config.php';
require_once __DIR__ . '/../../backend/includes/get_products.php';

// Call get_products() to populate the $products variable
$products = get_products();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <h1>Our Collection</h1>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="images/products/<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="price">$<?= number_format($product['price'], 2) ?></p>
                    <p><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                    <a href="product.php?id=<?= $product['id'] ?>" class="btn">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>