<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/db_connection.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';

require_admin();

/* ----------  stock update  ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $new_stock  = filter_input(INPUT_POST, 'stock',      FILTER_VALIDATE_INT);

    if ($product_id && $new_stock !== false && $new_stock >= 0) {
        $stmt = $mysqli->prepare("UPDATE products SET stock = ? WHERE id = ?");
        $stmt->bind_param('ii', $new_stock, $product_id);
        if ($stmt->execute()) {
            $success = 'Stock updated successfully.';
        } else {
            $error = 'Failed to update stock.';
        }
    }
}

/* ----------  data  ---------- */
$low   = $mysqli->query("SELECT * FROM products WHERE stock < 10 ORDER BY stock ASC")->fetch_all(MYSQLI_ASSOC);
$all   = $mysqli->query("SELECT * FROM products ORDER BY category, name")->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Inventory — EyeStore Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .fade-in{animation:fadeIn .6s ease-in-out}@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .table-row{cursor:pointer;transition:.15s}.table-row:hover{transform:scale(1.005)}
        .badge-critical{background:#dc3545;color:#fff}
        .badge-low    {background:#fd7e14;color:#fff}
        .badge-ok     {background:#198754;color:#fff}
    </style>
</head>
<body class="bg-light">

<?php include 'admin_header.php'; ?>

<div class="container-fluid py-4 fade-in">

    <!-- ======  TOP BAR  ====== -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-0 fw-bold">Inventory</h2>
            <p class="text-muted mb-0">Track stock levels and update quantities</p>
        </div>
        <a href="product_edit.php" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add Product
        </a>
    </div>

    <!-- ======  LOW-STOCK ALERT  ====== -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Low Stock Alert</h5>
        </div>
        <div class="card-body p-0">
            <?php if ($low): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th class="text-center">Stock</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($low as $item): ?>
                                <tr class="table-row">
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($item['category']) ?></span></td>
                                    <td class="text-center">
                                        <span class="badge <?= $item['stock'] <= 5 ? 'badge-critical' : 'badge-low' ?>"><?= $item['stock'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="product_edit.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-4 text-center text-muted">No low-stock items 🎉</div>
            <?php endif; ?>
        </div>
    </div><!-- /card -->

    <!-- ======  ALL PRODUCTS  ====== -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2"></i>All Products</h5>
            <span class="badge bg-primary rounded-pill"><?= count($all) ?> items</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Quick Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all as $item): ?>
                            <tr class="table-row">
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($item['category']) ?></span></td>
                                <td class="text-center">
                                    <span class="badge <?= $item['stock'] <= 5 ? 'badge-critical' : ($item['stock'] < 10 ? 'badge-low' : 'badge-ok') ?>"><?= $item['stock'] ?></span>
                                </td>
                                <td class="text-center">
                                    <form method="POST" class="d-inline-flex align-items-center gap-2">
                                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                        <input type="number" name="stock" value="<?= $item['stock'] ?>" min="0" class="form-control form-control-sm" style="width:90px">
                                        <button class="btn btn-sm btn-primary" name="update_stock" title="Update">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
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