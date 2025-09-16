<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';
require_once __DIR__ . '/../backend/includes/get_users.php';

require_admin();

$search = $_GET['search'] ?? '';
$users  = get_users($search);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Users — EyeStore Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .fade-in{animation:fadeIn .6s ease-in-out}@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .badge-admin{background:#6f42c1;color:#fff}
        .badge-customer{background:#20c997;color:#fff}
        .badge-inactive{background:#6c757d;color:#fff}
    </style>
</head>
<body class="bg-light">

<?php include 'admin_header.php'; ?>

<div class="container-fluid py-4 fade-in">
    <!-- breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Users</li>
        </ol>
    </nav>

    <!-- title bar + search -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between mb-4">
        <div>
            <h2 class="mb-0 fw-bold">User Management</h2>
            <p class="text-muted mb-0">Manage accounts, roles and activation status</p>
        </div>
        <form method="get" class="d-flex mt-3 mt-md-0" style="max-width:320px">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search users…" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-sm btn-outline-secondary ms-2">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <!-- users card -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['name']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <span class="badge <?= $u['is_admin'] ? 'badge-admin' : 'badge-customer' ?>">
                                    <?= $u['is_admin'] ? 'Admin' : 'Customer' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= $u['is_active'] ? 'bg-success' : 'badge-inactive' ?>">
                                    <?= $u['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="user_edit.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- /card -->

</div><!-- /container -->

<?php include 'admin_footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>