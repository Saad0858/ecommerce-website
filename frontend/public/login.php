<?php require_once __DIR__ . '/../../backend/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <?php if (isset($_GET['registered'])): ?>
            <div class="alert success">Registration successful! Please login.</div>
        <?php endif; ?>
        <h1>Login to Your Account</h1>
        <form action="../../backend/login.php" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>
</html>