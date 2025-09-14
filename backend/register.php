<?php
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input validation
    $name = trim($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    
    // Validate other fields similarly
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Database insertion
    $stmt = $mysqli->prepare("INSERT INTO users (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $hashed_password, $phone, $address);
    
    if ($stmt->execute()) {
        header("Location: " . BASE_URL . "/login.php?registered=1");
        exit;
    } else {
        $error = "Registration failed: " . $mysqli->error;
    }
}
?>