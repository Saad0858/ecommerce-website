<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';
require_once __DIR__ . '/../backend/includes/get_sales_data.php';

require_admin();

$salesData = get_sales_summary();
$recentOrders = get_recent_orders(5);

/* ----------  optional card gradient  ---------- */
$cardGrad = [
    'today'  => 'bg-gradient-primary',
    'month'  => 'bg-gradient-success',
    'orders' => 'bg-gradient-info'
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard — EyeStore Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 + icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- custom admin skin -->
    <link rel="stylesheet" href="../assets/css/admin.css">

    <style>
        /* subtle animation */
        .stat-card{transition:.25s ease;}
        .stat-card:hover{transform:translateY(-4px);}
    </style>
</head>
<body class="bg-light">

<?php include 'admin_header.php'; ?>
<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <h1 class="display-4 fw-bold">
            Welcome  <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
        </h1>
        <p class="lead">Discover amazing products at great prices</p>
    </div>
</div>
<div class="container-fluid py-4">

    <!-- ==========  TOP CARDS  ========== -->
    <div class="row g-4 mb-4">

        <!-- Today -->
        <div class="col-md-4">
            <div class="card text-white <?= $cardGrad['today'] ?> stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h2 class="mb-0">$<?= number_format($salesData['daily_sales'],2) ?></h2>
                        <p class="mb-0 opacity-75">Today's Sales</p>
                    </div>
                    <i class="bi bi-currency-dollar fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <!-- Month -->
        <div class="col-md-4">
            <div class="card text-white <?= $cardGrad['month'] ?> stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h2 class="mb-0">$<?= number_format($salesData['monthly_sales'],2) ?></h2>
                        <p class="mb-0 opacity-75">This Month</p>
                    </div>
                    <i class="bi bi-calendar-month fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="col-md-4">
            <div class="card text-white <?= $cardGrad['orders'] ?> stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h2 class="mb-0"><?= $salesData['total_orders'] ?></h2>
                        <p class="mb-0 opacity-75">Total Orders</p>
                    </div>
                    <i class="bi bi-cart-check fs-1 opacity-75"></i>
                </div>
            </div>
        </div>
    </div><!-- /row -->

    <!-- ==========  QUICK ACTIONS  ========== -->
    <div class="row mb-4">
        <div class="col-md-4">
            <a href="product_edit.php" class="btn btn-primary w-100">
                <i class="bi bi-plus-circle me-1"></i> Add New Product
            </a>
        </div>
        <div class="col-md-4">
            <a href="inventory.php" class="btn btn-outline-secondary w-100">
                <i class="bi bi-box-seam me-1"></i> View Inventory
            </a>
        </div>
        <div class="col-md-4">
            <a href="reports.php" class="btn btn-outline-dark w-100">
                <i class="bi bi-graph-up me-1"></i> Generate Report
            </a>
        </div>
    </div>

    <!-- ==========  RECENT ORDERS  ========== -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Recent Orders</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th class="text-end">Amount</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $o): ?>
                        <tr>
                            <td><?= $o['id'] ?></td>
                            <td><?= htmlspecialchars($o['name']) ?></td>
                            <td class="text-end fw-semibold">
                                ₹<?= number_format($o['total_amount'],2) ?>
                            </td>
                            <td><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
                            <td class="text-center">
                                <a href="order_details.php?id=<?= $o['id'] ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    View
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

<!-- ----------  FOOTER  ---------- -->
<?php include 'admin_footer.php'; ?>

<!-- ----------  JS  ---------- -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>