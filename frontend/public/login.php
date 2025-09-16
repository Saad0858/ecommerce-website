<?php
require_once __DIR__ . '/../../backend/includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — <?= SITE_NAME ?></title>

    <!-- Bootstrap 5 + icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- site skin -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        /* subtle fade-in */
        .login-card{animation:fadeIn .7s ease-in-out}
        @keyframes fadeIn{from{opacity:0;transform:translateY(-15px)}to{opacity:1;transform:translateY(0)}}
    </style>
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-5 col-lg-4">

            <!-- ===== card ===== -->
            <div class="card shadow login-card">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <i class="bi bi-person-circle fs-1 text-primary"></i>
                        <h4 class="mt-2 fw-bold">Welcome back</h4>
                        <p class="text-muted mb-0">Sign in to your account</p>
                    </div>

                    <!-- feedback -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-sm"><?= $_SESSION['error'] ?></div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    <?php if (isset($_GET['registered'])): ?>
                        <div class="alert alert-success alert-sm">Registration successful! Please login.</div>
                    <?php endif; ?>

                    <!-- form -->
                    <form action="../../backend/login.php" method="POST" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Don't have an account?
                            <a href="register.php" class="text-decoration-none">Register here</a>
                        </small>
                    </div>

                </div><!-- /card-body -->
            </div><!-- /card -->

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>