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
    <?php include __DIR__ . "/../public/includes/header.php"; ?>
    
    <div class="container">
    <div class="row">
        <?php foreach ($products as $product): ?>
        <div class="col-md-4 mb-4">
            <div class="card product-card h-100">
            <img src="<?= $product['image_url'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
            <div class="card-body text-center">
                <h5 class="card-title"><?= $product['name'] ?></h5>
                <p class="card-text text-muted">$<?= number_format($product['price'], 2) ?></p>
                <a href="cart.php?action=add&id=<?= $product['id'] ?>" class="btn btn-primary">Add to Cart</a>
            </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    </div>

    
    <?php include __DIR__ . "/../public/includes/footer.php"; ?>
</body>
</html>