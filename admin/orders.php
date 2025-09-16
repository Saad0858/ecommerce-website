<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/db_connection.php'; // <-- Add this
require_once __DIR__ . '/../backend/includes/auth_check.php';
require_once __DIR__ . '/../backend/includes/get_orders.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


require_admin();
$orders = get_orders();   // make sure we have the list

// Handle status update if form was submitted
// Handle status update if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'], $_POST['order_id'])) {
    $order_id = (int) $_POST['order_id'];
    $status   = $_POST['status'];

    $allowed_statuses = ['pending', 'processing', 'completed', 'cancelled'];

    if ($order_id > 0 && in_array($status, $allowed_statuses, true)) {
        $stmt = $mysqli->prepare("UPDATE orders SET status = ? WHERE id = ?");
        if (!$stmt) {
            die("Prepare failed: " . $mysqli->error);
        }

        $stmt->bind_param("si", $status, $order_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Order #$order_id status updated to $status.";
        } else {
            $_SESSION['error_message'] = "DB error: " . $stmt->error;
        }

        $stmt->close();

        // Redirect to avoid resubmission and show updated list
        header("Location: orders.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Invalid status or order ID.";
    }
}

    error_log("POST STATUS: " . ($_POST['status'] ?? 'MISSING'));


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Orders — EyeStore Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .fade-in{animation:fadeIn .6s ease-in-out}@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .badge-pending  {background:#ffc107;color:#212529}
        .badge-processing{background:#17a2b8;color:#fff}
        .badge-completed {background:#28a745;color:#fff}
        .badge-cancelled {background:#dc3545;color:#fff}
    </style>
</head>
<body class="bg-light">

<?php include 'admin_header.php'; ?>

<div class="container-fluid py-4 fade-in">
    <!-- breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Orders</li>
        </ol>
    </nav>

    <!-- title bar -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-0 fw-bold">Order Management</h2>
            <p class="text-muted mb-0">Update status, view details & track shipments</p>
        </div>
    </div>

    <!-- Display messages -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success_message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error_message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- orders card -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th class="text-end">Amount</th>
                            <th class="text-center">Current Status</th>
                            <th>Change Status</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><?= $o['id'] ?></td>
                            <td><?= htmlspecialchars($o['email']) ?></td>
                            <td class="text-end fw-semibold">₹<?= number_format($o['total_amount'], 2) ?></td>
                            <td class="text-center">
                                <span class="status-<?= $status_class ?> me-2">
                                    <?= $o['status'] ?>
                                </span>
                            </td>
                            <td>
                                <!-- status badge + quick-change form -->
                                <form method="POST" class="d-flex align-items-center gap-1">
                                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="Select Status"    <?= $o['status'] === 'pending'    ? 'selected' : '' ?>>Select Status</option>
                                        <option value="pending"    <?= $o['status'] === 'pending'    ? 'selected' : '' ?>>Pending</option>
                                        <option value="processing" <?= $o['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                        <option value="completed"  <?= $o['status'] === 'completed'  ? 'selected' : '' ?>>Completed</option>
                                        <option value="cancelled"  <?= $o['status'] === 'cancelled'  ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </form>

                            </td>
                            <td><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
                            <td class="text-center">
                                <a href="order_details.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
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