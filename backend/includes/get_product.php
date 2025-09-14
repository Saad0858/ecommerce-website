<?php
require_once __DIR__ . '/db_connection.php';

$product_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

if ($product_id) {
    $stmt = $mysqli->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}

function get_related_products($productId, $limit = 4) {
    global $mysqli;
    
    $stmt = $mysqli->prepare("SELECT p.* 
        FROM products p
        INNER JOIN products current ON p.category = current.category
        WHERE p.id != ?
        LIMIT ?");
    $stmt->bind_param("ii", $productId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}