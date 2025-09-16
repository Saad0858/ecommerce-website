<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';
require_once __DIR__ . '/../backend/includes/get_sales_data.php';

require_admin();

$startDate = filter_input(INPUT_GET, 'start_date');
$endDate = filter_input(INPUT_GET, 'end_date');

$salesReport = get_sales_report($startDate, $endDate);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sales Reports — EyeStore Admin</title>
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
            <li class="breadcrumb-item active">Sales Reports</li>
        </ol>
    </nav>

    <!-- title bar -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-0 fw-bold">Sales Reports</h2>
            <p class="text-muted mb-0">Generate sales reports by date range</p>
        </div>
    </div>

    <!-- report filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form class="report-filters" method="GET">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">From</label>
                        <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">To</label>
                        <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Generate Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div><!-- /card -->

    <!-- sales report table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3 fw-bold">Sales Summary</h5>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Orders</th>
                            <th>Total Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($salesReport as $day): ?>
                        <tr>
                            <td><?= date('M j, Y', strtotime($day['order_date'])) ?></td>
                            <td><?= $day['order_count'] ?></td>
                            <td>$<?= number_format($day['daily_total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($salesReport)): ?>
                        <tr>
                            <td colspan="3">No sales data found for the selected period</td>
                        </tr>
                    <?php endif; ?>
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