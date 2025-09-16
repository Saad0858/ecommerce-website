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
    
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold">Welcome to Our E-Commerce Store</h1>
            <p class="lead">Discover amazing products at great prices</p>
            <a href="<?= BASE_URL ?>/products.php" class="btn btn-primary btn-lg mt-3">
                <i class="fas fa-shopping-bag me-2"></i>Shop Now
            </a>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        
        <div class="row">
            
        </div>
    </div>


    <div class="container">
    <div class="row">
        <?php foreach ($products as $product): ?>
        <div class="col-md-4 mb-4">
            <div class="product-card">
                <img src="<?= $product['image_url'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
                <div class="product-body">
                    <h3 class="product-title"><?= $product['name'] ?></h5>
                    <p class="product-price">₹<?= number_format($product['price'], 2) ?></p>
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