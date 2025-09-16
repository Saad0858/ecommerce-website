<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';
require_once __DIR__ . '/../backend/includes/get_products.php';

require_admin();  
$products = get_products(); 

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Products — EyeStore Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* subtle fade-in */
        .fade-in{animation:fadeIn .6s ease-in-out}@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .table-hover tbody tr{cursor:pointer}.badge-low{color:#842029;background:#f8d7da}.badge-ok{color:#0f5132;background:#d1e7dd}
    </style>
</head>
<body class="bg-light">

<?php include 'admin_header.php'; ?>

<!-- ======  TOP BAR  ====== -->
<div class="container-fluid py-3 fade-in">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-0 fw-bold">Products</h2>
            <p class="text-muted mb-0">Manage catalogue, pricing & inventory</p>
        </div>
        <a href="product_edit.php" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add New Product
        </a>
    </div>

    <!-- ======  PRODUCTS CARD  ====== -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th class="text-end">Price</th>
                            <th class="text-center">Stock</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $p): ?>
                        <?php
                        // stock badge
                        if ($p['stock'] == 0) {
                            $stockHtml = '<span class="badge bg-danger">Out of stock</span>';
                        } elseif ($p['stock'] < 10) {
                            $stockHtml = '<span class="badge badge-low">Low</span> ' . $p['stock'];
                        } else {
                            $stockHtml = '<span class="badge badge-ok">' . $p['stock'] . '</span>';
                        }
                        ?>
                        <tr onclick="window.location='product_edit.php?id=<?= $p['id'] ?>'">
                            <td><?= $p['id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if ($p['image_url']): ?>
                                        <img src="<?= $p['image_url'] ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="rounded me-3" style="width:48px;height:48px;object-fit:cover">
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($p['name']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars(substr($p['description'], 0, 50)) ?>…</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($p['category']) ?></span></td>
                            <td class="text-end fw-semibold">₹<?= number_format($p['price'], 2) ?></td>
                            <td class="text-center"><?= $stockHtml ?></td>
                            <td class="text-center">
                                <!-- actions -->
                                <a href="product_edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation()">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="product_delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Delete this product?');event.stopPropagation()">
                                    <i class="bi bi-trash"></i>
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