<?php require_once __DIR__ . '/../../backend/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .fade-in{animation:fadeIn .6s ease-in-out}@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .register-container { max-width: 400px; margin: 50px auto; padding: 20px; background: #f8f9fa; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .register-container h1 { text-align: center; margin-bottom: 20px; }
        .register-container form { display: flex; flex-direction: column; }
        .register-container form input, .register-container form button { margin-bottom: 15px; }
        .register-container form button { background-color: #0d6efd; border-color: #0d6efd; }
        .register-container form button:hover { background-color: #0b5ed7; border-color: #0b5ed7; }
        .register-container p { text-align: center; }
        .register-container p a { color: #0d6efd; text-decoration: none; }
        .register-container p a:hover { text-decoration: underline; }
    </style>
</head>
<body class="bg-light">
    <div class="register-container fade-in">
        <h1>Create Account</h1>
        <form action="../../backend/register.php" method="post">
            <input type="text" name="name" placeholder="Full Name" class="form-control" required>
            <input type="email" name="email" placeholder="Email" class="form-control" required>
            <input type="password" name="password" placeholder="Password" class="form-control" required>
            <input type="text" name="phone" placeholder="Phone" class="form-control">
            <input type="text" name="address" placeholder="Address" class="form-control">
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>