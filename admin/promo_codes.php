<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/db_connection.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';

require_admin();

// Handle form submission for new/edit promo code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper(filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING));
    $discount = filter_input(INPUT_POST, 'discount_percent', FILTER_VALIDATE_FLOAT);
    $min_amount = filter_input(INPUT_POST, 'min_order_amount', FILTER_VALIDATE_FLOAT);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $start_date = filter_input(INPUT_POST, 'start_date');
    $end_date = filter_input(INPUT_POST, 'end_date');
    $usage_limit = filter_input(INPUT_POST, 'usage_limit', FILTER_VALIDATE_INT);

    if (empty($usage_limit)) {
        $usage_limit = NULL;
    }

    if (isset($_POST['id'])) {
        // Update existing promo code
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $stmt = $mysqli->prepare("UPDATE promo_codes SET code = ?, discount_percent = ?, min_order_amount = ?, is_active = ?, start_date = ?, end_date = ?, usage_limit = ? WHERE id = ?");
        $stmt->bind_param("sddiisii", $code, $discount, $min_amount, $is_active, $start_date, $end_date, $usage_limit, $id);
    } else {
        // Insert new promo code
        $stmt = $mysqli->prepare("INSERT INTO promo_codes (code, discount_percent, min_order_amount, is_active, start_date, end_date, usage_limit) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sddiisi", $code, $discount, $min_amount, $is_active, $start_date, $end_date, $usage_limit);
    }

    if ($stmt->execute()) {
        $success_message = "Promo code saved successfully";
    } else {
        $error_message = "Error saving promo code: " . $mysqli->error;
    }
}

// Delete promo code
if (isset($_GET['delete'])) {
    $id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $mysqli->prepare("DELETE FROM promo_codes WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $success_message = "Promo code deleted successfully";
        } else {
            $error_message = "Error deleting promo code";
        }
    }
}

// Get all promo codes
$stmt = $mysqli->prepare("SELECT * FROM promo_codes ORDER BY end_date DESC");
$stmt->execute();
$result = $stmt->get_result();
$promo_codes = $result->fetch_all(MYSQLI_ASSOC);

// Edit promo code
$editing = false;
if (isset($_GET['edit'])) {
    $id = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
    if ($id) {
        $stmt = $mysqli->prepare("SELECT * FROM promo_codes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($promo = $result->fetch_assoc()) {
            $editing = true;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Promo Code Management — EyeStore Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .fade-in{animation:fadeIn .6s ease-in-out}@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .badge-active{background:#28a745;color:#fff}
        .badge-inactive{background:#dc3545;color:#fff}
    </style>
</head>
<body class="bg-light">

<?php include 'admin_header.php'; ?>

<div class="container-fluid py-4 fade-in">
    <!-- breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Promo Codes</li>
        </ol>
    </nav>

    <!-- title bar -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-0 fw-bold"><?= $editing ? 'Edit' : 'Add' ?> Promo Code</h2>
            <p class="text-muted mb-0">Create or update discount codes</p>
        </div>
    </div>

    <!-- feedback -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= $success_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i><?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- form card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <?php if ($editing): ?>
                    <input type="hidden" name="id" value="<?= $promo['id'] ?>">
                <?php endif; ?>

                <div class="col-md-6">
                    <label class="form-label">Promo Code</label>
                    <input type="text" name="code" class="form-control" value="<?= $editing ? $promo['code'] : '' ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Discount Percentage</label>
                    <input type="number" name="discount_percent" class="form-control" min="0" max="100" step="0.01" value="<?= $editing ? $promo['discount_percent'] : '' ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Minimum Order Amount</label>
                    <input type="number" name="min_order_amount" class="form-control" min="0" step="0.01" value="<?= $editing ? $promo['min_order_amount'] : '0' ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Active</label>
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_active" class="form-check-input" <?= (!$editing || $promo['is_active']) ? 'checked' : '' ?>>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?= $editing ? $promo['start_date'] : date('Y-m-d') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="<?= $editing ? $promo['end_date'] : date('Y-m-d', strtotime('+30 days')) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Usage Limit (blank for unlimited)</label>
                    <input type="number" name="usage_limit" class="form-control" min="1" value="<?= $editing && $promo['usage_limit'] ? $promo['usage_limit'] : '' ?>">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><?= $editing ? 'Update' : 'Add' ?> Promo Code</button>
                </div>
            </form>
        </div>
    </div><!-- /card -->

   <!-- existing promo codes -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="mb-3 fw-bold">Existing Promo Codes</h5>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Discount %</th>
                            <th>Min. Amount</th>
                            <th>Status</th>
                            <th>Validity</th>
                            <th>Usage</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($promo_codes as $code): ?>
                        <tr>
                            <td><?= htmlspecialchars($code['code']) ?></td>
                            <td><?= $code['discount_percent'] ?>%</td>
                            <td>₹<?= number_format($code['min_order_amount'] ?? 0, 2) ?></td>
                            <td>
                                <span class="badge <?= $code['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                                    <?= $code['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <?= date('M d, Y', strtotime($code['start_date'])) ?> - 
                                <?= date('M d, Y', strtotime($code['end_date'])) ?>
                            </td>
                            <td>
                                <?= $code['usage_count'] ?? 0 ?>
                                <?= $code['usage_limit'] ? '/' . $code['usage_limit'] : '' ?>
                            </td>
                            <td>
                                <a href="?edit=<?= $code['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="?delete=<?= $code['id'] ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this promo code?')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (count($promo_codes) === 0): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-tag"></i>
                                    <h4>No promo codes found</h4>
                                    <p>Get started by creating your first promo code</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- /card -->



<!-- /card -->
</div><!-- /container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>