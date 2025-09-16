<?php
require_once __DIR__ . '/db_connection.php';

function get_orders() {
    global $mysqli;
    
    $sql = "SELECT id, email, total_amount, status, created_at 
            FROM orders 
            ORDER BY created_at DESC";
    
    $result = $mysqli->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}
