<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <nav>
            <a href="<?= BASE_URL ?>/products.php">Home</a>
            <a href="<?= BASE_URL ?>/cart.php">Cart</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/account.php">Account</a>
                <a href="<?= BASE_URL ?>/../../backend/logout.php">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login.php">Login</a>
                <a href="<?= BASE_URL ?>/register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>