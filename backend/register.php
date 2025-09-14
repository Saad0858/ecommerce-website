<?php
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and validate inputs
    $name = trim($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (!$name || !$email || !$password) {
        die("All required fields must be filled.");
    }

    if (strlen($password) < 6) {
        die("Password must be at least 6 characters.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare("INSERT INTO users (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $hashed_password, $phone, $address);

    if ($stmt->execute()) {
        echo "Registration successful!";
        header("Location: ../frontend/public/login.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>