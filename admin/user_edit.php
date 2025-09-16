<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';
require_once __DIR__ . '/../backend/includes/db_connection.php';

require_admin();

$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($user_id) {
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $is_active = filter_input(INPUT_POST, 'is_active', FILTER_VALIDATE_BOOLEAN);
    $is_admin = filter_input(INPUT_POST, 'is_admin', FILTER_VALIDATE_BOOLEAN);

    $stmt = $mysqli->prepare("UPDATE users SET name = ?, email = ?, is_active = ?, is_admin = ? WHERE id = ?");
    $stmt->bind_param("ssiii", $name, $email, $is_active, $is_admin, $user_id);
    $stmt->execute();

    header("Location: users.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit User — EyeStore Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .fade-in{animation:fadeIn .6s ease-in-out}@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    </style>
</head>
<body class="bg-light">

<?php include 'admin_header.php'; ?>

<div class="container-fluid py-4 fade-in">
    <!-- breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="users.php">Users</a></li>
            <li class="breadcrumb-item active">Edit User</li>
        </ol>
    </nav>

    <!-- title bar -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-0 fw-bold">Edit User</h2>
            <p class="text-muted mb-0">Update user details, roles and status</p>
        </div>
        <a href="users.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <!-- form card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Active</label>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" <?= $user['is_active'] ? 'checked' : '' ?>>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Administrator</label>
                    <div class="form-check">
                        <input type="checkbox" name="is_admin" class="form-check-input" <?= $user['is_admin'] ? 'checked' : '' ?>>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div><!-- /card -->

</div><!-- /container -->

<?php include 'admin_footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>