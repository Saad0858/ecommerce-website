<?php
function get_order_details($orderNumber, $email) {
    global $mysqli;
    
    $stmt = $mysqli->prepare("SELECT 
            orders.*, 
            GROUP_CONCAT(products.name SEPARATOR ', ') AS items
        FROM orders
        JOIN order_items ON orders.id = order_items.order_id
        JOIN products ON order_items.product_id = products.id
        WHERE order_number = ? AND email = ?");
    $stmt->bind_param("ss", $orderNumber, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}