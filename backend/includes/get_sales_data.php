<?php
function get_sales_summary() {
    global $mysqli;
    
    $query = "SELECT 
        SUM(CASE WHEN DATE(created_at) = CURDATE() THEN total_amount ELSE 0 END) AS daily_sales,
        SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) THEN total_amount ELSE 0 END) AS monthly_sales,
        COUNT(*) AS total_orders 
        FROM orders";
    
    $result = $mysqli->query($query);
    return $result->fetch_assoc();
}

function get_recent_orders($limit = 5) {
    global $mysqli;
    
    $stmt = $mysqli->prepare("SELECT o.id, u.name, o.total_amount, o.created_at 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC 
        LIMIT ?");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_sales_report($startDate = null, $endDate = null) {
    global $mysqli;
    
    $query = "SELECT 
        DATE(created_at) AS order_date,
        COUNT(*) AS order_count,
        SUM(total_amount) AS daily_total 
        FROM orders 
        WHERE 1=1";
    
    $params = [];
    $types = '';
    
    if ($startDate) {
        $query .= " AND DATE(created_at) >= ?";
        $params[] = $startDate;
        $types .= 's';
    }
    
    if ($endDate) {
        $query .= " AND DATE(created_at) <= ?";
        $params[] = $endDate;
        $types .= 's';
    }
    
    $query .= " GROUP BY DATE(created_at) ORDER BY order_date DESC";
    
    $stmt = $mysqli->prepare($query);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}