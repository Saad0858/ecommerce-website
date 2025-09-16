<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/db_connection.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';

require_admin();

$success_count = 0;
$error_count   = 0;
$errors        = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['inventory_file'])) {
    $file = $_FILES['inventory_file'];

    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($file_ext !== 'csv') {
        $error_message = "Please upload a CSV file.";
    } elseif ($file['error'] !== 0) {
        $error_message = "Error uploading file. Code: " . $file['error'];
    } else {
        if (($handle = fopen($file['tmp_name'], "r")) !== false) {
            $mysqli->begin_transaction();
            try {
                $header = fgetcsv($handle, 1000, ",");
                $expected = ['product_id', 'stock'];
                if (count(array_intersect($header, $expected)) !== 2) {
                    throw new Exception("CSV must contain columns: product_id, stock");
                }

                $row_number = 1;
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    $row_number++;
                    $product_id = filter_var(trim($data[0]), FILTER_VALIDATE_INT);
                    $stock      = filter_var(trim($data[1]), FILTER_VALIDATE_INT);

                    if ($product_id === false || $stock === false || $stock < 0) {
                        $errors[] = "Row $row_number: Invalid product ID or stock value";
                        $error_count++;
                        continue;
                    }

                    $check = $mysqli->prepare("SELECT id FROM products WHERE id = ?");
                    $check->bind_param("i", $product_id);
                    $check->execute();
                    if ($check->get_result()->num_rows === 0) {
                        $errors[] = "Row $row_number: Product ID $product_id not found";
                        $error_count++;
                        continue;
                    }

                    $upd = $mysqli->prepare("UPDATE products SET stock = ? WHERE id = ?");
                    $upd->bind_param("ii", $stock, $product_id);
                    if ($upd->execute()) {
                        $success_count++;
                    } else {
                        $errors[] = "Row $row_number: DB error";
                        $error_count++;
                    }
                }

                if ($success_count === 0 || ($error_count > $success_count)) {
                    throw new Exception("Too many errors – no changes applied.");
                }
                $mysqli->commit();
                $success_message = "Inventory updated. $success_count rows applied.";
            } catch (Exception $e) {
                $mysqli->rollback();
                $error_message = $e->getMessage();
            }
            fclose($handle);
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Batch Inventory — EyeStore Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .fade-in{animation:fadeIn .6s ease-in-out}@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .drop-area{border:2px dashed #6c757d;border-radius:.5rem;padding:2rem;text-align:center;transition:.25s}
        .drop-area.dragover{background:#f8f9fa;border-color:#0d6efd}
        .progress-thin{height:.4rem}
    </style>
</head>
<body class="bg-light">

<?php include 'admin_header.php'; ?>

<div class="container-fluid py-4 fade-in">
    <!-- breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="inventory.php">Inventory</a></li>
            <li class="breadcrumb-item active">Batch Update</li>
        </ol>
    </nav>

    <!-- title bar -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-0 fw-bold">Batch Inventory Update</h2>
            <p class="text-muted mb-0">Upload a CSV to update stock levels in seconds</p>
        </div>
        <a href="download_template.php" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-download me-1"></i>Download Template
        </a>
    </div>

    <!-- feedback cards -->
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

    <!-- results bar -->
    <?php if ($success_count || $error_count): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Processing Summary</h5>
                <div class="row text-center">
                    <div class="col">
                        <div class="text-success fw-bold fs-4"><?= $success_count ?></div>
                        <small class="text-muted">Successful</small>
                        <div class="progress progress-thin mt-1">
                            <div class="progress-bar bg-success" style="width:<?= $success_count ? min(100, ($success_count / ($success_count + $error_count)) * 100) : 0 ?>%"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-danger fw-bold fs-4"><?= $error_count ?></div>
                        <small class="text-muted">Errors</small>
                        <div class="progress progress-thin mt-1">
                            <div class="progress-bar bg-danger" style="width:<?= $error_count ? min(100, ($error_count / ($success_count + $error_count)) * 100) : 0 ?>%"></div>
                        </div>
                    </div>
                </div>

                <?php if ($errors): ?>
                    <hr>
                    <h6 class="mb-2">Error Details</h6>
                    <div style="max-height:200px;overflow-y:auto;">
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($errors as $e): ?>
                                <li class="text-danger small"><i class="bi bi-circle-fill me-1" style="font-size:6px"></i><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- upload card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" id="csvForm">
                <div class="mb-3">
                    <label class="form-label fw-semibold">1. Select CSV file</label>
                    <div class="drop-area" id="dropArea">
                        <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                        <p class="mb-0">Drag & drop here or <span class="text-primary">browse</span></p>
                        <input type="file" name="inventory_file" id="fileInput" accept=".csv" required hidden>
                    </div>
                </div>

                <div class="d-grid col-md-3 mx-auto">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-upload me-2"></i>Upload & Process
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- instructions -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Instructions</h5>
            <ol class="mb-0">
                <li>Columns must be <strong>product_id</strong> and <strong>stock</strong> (header row optional).</li>
                <li>All product IDs must already exist in the catalogue.</li>
                <li>Stock values must be ≥ 0.</li>
                <li>If more than 50 % of rows fail, the entire batch is rolled back.</li>
            </ol>
        </div>
    </div>

</div><!-- /container -->

<?php include 'admin_footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // drag-and-drop cosmetics
    const dropArea = document.getElementById('dropArea');
    const fileInp  = document.getElementById('fileInput');
    dropArea.addEventListener('click', () => fileInp.click());
    fileInp.addEventListener('change', () => dropArea.textContent = fileInp.files[0].name);
</script>
</body>
</html>