<?php
require_once __DIR__ . '/db_connection.php';

function get_users($search = null) {
    global $mysqli; // Access the global $mysqli variable

    $query = "SELECT id, name, email, role, is_active, is_admin FROM users WHERE 1=1";
    $params = [];
    $types = '';

    if ($search) {
        $query .= " AND (name LIKE ? OR email LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'ss';
    }

    $query .= " ORDER BY created_at DESC"; // Assuming you will add the created_at column

    $stmt = $mysqli->prepare($query);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}