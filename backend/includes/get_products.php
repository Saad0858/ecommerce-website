<?php
require_once 'db_connection.php';

/**
 * Get all products or filter by category
 * @param string|null $category Optional category filter
 * @return array Array of products
 */
function get_products($category = null) {
    global $mysqli;
    
    $query = "SELECT * FROM products";
    $params = [];
    
    if ($category) {
        $query .= " WHERE category = ?";
        $params[] = $category;
    }
    
    $query .= " ORDER BY name ASC";
    
    $stmt = $mysqli->prepare($query);
    
    if (!empty($params)) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    return $products;
}

/**
 * Get featured or newest products
 * @param int $limit Number of products to return
 * @return array Array of products
 */
function get_featured_products($limit = 4) {
    global $mysqli;
    
    $query = "SELECT * FROM products ORDER BY id DESC LIMIT ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    return $products;
}
?>