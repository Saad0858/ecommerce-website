<?php
function get_users($search = null) {
    global $mysqli;
    
    $query = "SELECT id, name, email, role, created_at FROM users WHERE 1=1";
    $params = [];
    $types = '';
    
    if ($search) {
        $query .= " AND (name LIKE ? OR email LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'ss';
    }
    
    $query .= " ORDER BY created_at DESC";
    
    $stmt = $mysqli->prepare($query);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}