<?php
require_once __DIR__ . '/../../backend/includes/config.php';
require_once __DIR__ . '/../../backend/includes/db_connection.php';
require_once __DIR__ . '/../../backend/includes/get_product.php';

// Get product stock status
$stock_status = "";
if ($product['stock'] > 10) {
    $stock_status = "<span class='in-stock'>In Stock</span>";
} elseif ($product['stock'] > 0) {
    $stock_status = "<span class='low-stock'>Low Stock - Only {$product['stock']} left</span>";
} else {
    $stock_status = "<span class='out-of-stock'>Out of Stock</span>";
}
?>

<!DOCTYPE html>
<html>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <div class="product-detail">
            <div class="product-image">
                <img src="<?= $product['image_url'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
            
            <div class="product-info">
                <h1><?= htmlspecialchars($product['name']) ?></h1>
                <p class="product-price">$<?= number_format($product['price'], 2) ?></p>
                <p class="product-stock"><?= $stock_status ?></p>
                <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                
                <?php if ($product['stock'] > 0): ?>
                    <form action="../../backend/add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <div class="form-group">
                            <label>Quantity:</label>
                            <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>">
                        </div>
                        <button type="submit" class="btn">Add to Cart</button>
                    </form>
                <?php else: ?>
                    <button class="btn disabled" disabled>Out of Stock</button>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="reviews-section">
            <h3>Customer Reviews</h3>
            <?php foreach ($reviews as $review): ?>
            <div class="review">
                <div class="rating"><?= str_repeat('★', $review['rating']) ?></div>
                <p><?= htmlspecialchars($review['comment']) ?></p>
                <small><?= date('M d, Y', strtotime($review['created_at'])) ?></small>
            </div>
            <?php endforeach; ?>
            
            <?php if ($isLoggedIn): ?>
            <form method="post" action="/backend/submit_review.php">
                <h4>Write a Review</h4>
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <select name="rating" required>
                    <option value="5">★★★★★</option>
                    <option value="4">★★★★✩</option>
                    <!-- More options -->
                </select>
                <textarea name="comment" placeholder="Your review" required></textarea>
                <button type="submit" class="btn">Submit Review</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>