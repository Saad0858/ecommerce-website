<?php
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$email) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: " . BASE_URL . "/login.php");
        exit;
    }

    $stmt = $mysqli->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            // Successful login
            // set consistent session keys used across the app
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            // unify to 'role' because auth_check() expects $_SESSION['role']
            $_SESSION['role'] = $user['role'];
            $_SESSION['is_admin']  = $user['is_admin'];
            
            // Regenerate session ID to prevent fixation
            session_regenerate_id(true);
            
            if ($user['is_admin']) {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../frontend/public/account.php");
            }
            exit;

        }
    }

    // Failed login
    $_SESSION['error'] = "Invalid email or password";
    header("Location: " . BASE_URL . "/login.php");
    exit;
}
?>