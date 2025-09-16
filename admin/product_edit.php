<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';
require_once __DIR__ . '/../backend/includes/db_connection.php';

require_admin();

$product = null;
$editing = isset($_GET['id']);

if ($editing) {
    $id = (int)$_GET['id'];
    $stmt = $mysqli->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
}

/* ----------  handle form POST  ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
    $cat   = $_POST['category'] ?? '';
    $desc  = trim($_POST['description'] ?? '');

    // basic validation
    if (!$name || $price === false || $price < 0 || $stock === false || $stock < 0 || !in_array($cat, ['spectacles','sunglasses','watches'])) {
        $error = 'Please fill all fields correctly.';
    } else {
        // image upload
        $imageUrl = $product['image_url'] ?? '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['image'];
            $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $newName = uniqid('p_', true) . ".$ext";
                $dest    = __DIR__ . '/../uploads/product/' . $newName;
                if (!is_dir(dirname($dest))) mkdir(dirname($dest), 0777, true);
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $imageUrl = '/uploads/product/' . $newName;
                }
            }
        }

        if ($editing) {
            $stmt = $mysqli->prepare("UPDATE products SET name=?, category=?, description=?, price=?, stock=?, image_url=? WHERE id=?");
            $stmt->bind_param('sssdisi', $name, $cat, $desc, $price, $stock, $imageUrl, $id);
        } else {
            $stmt = $mysqli->prepare("INSERT INTO products (name,category,description,price,stock,image_url) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param('ssssis', $name, $cat, $desc, $price, $stock, $imageUrl);
        }
        $stmt->execute();
        $_SESSION['success'] = $editing ? 'Product updated.' : 'Product added.';
        header('Location: products.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $editing ? 'Edit' : 'Add' ?> Product — EyeStore Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .fade-in{animation:fadeIn .6s ease-in-out}@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .img-preview{max-height:200px;object-fit:cover}
    </style>
</head>
<body class="bg-light">

<?php include 'admin_header.php'; ?>

<div class="container py-4 fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- title -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="mb-0 fw-bold"><?= $editing ? 'Edit Product' : 'Add New Product' ?></h2>
                <a href="products.php" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>

            <!-- feedback -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <!-- form card -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select" required>
                                    <option value="spectacles" <?= ($product['category'] ?? '') === 'spectacles' ? 'selected' : '' ?>>Spectacles</option>
                                    <option value="sunglasses" <?= ($product['category'] ?? '') === 'sunglasses' ? 'selected' : '' ?>>Sunglasses</option>
                                    <option value="watches" <?= ($product['category'] ?? '') === 'watches' ? 'selected' : '' ?>>Watches</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Price ($)</label>
                                <input type="number" step="0.01" min="0" name="price" class="form-control" value="<?= $product['price'] ?? '' ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stock</label>
                                <input type="number" min="0" name="stock" class="form-control" value="<?= $product['stock'] ?? '' ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Product Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*"
                                   onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])">
                            <?php if (!empty($product['image_url'])): ?>
                                <img src="<?= htmlspecialchars($product['image_url']) ?>" id="preview" class="img-fluid img-preview rounded mt-2">
                            <?php else: ?>
                                <img id="preview" class="img-fluid img-preview rounded mt-2" style="display:none;">
                            <?php endif; ?>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i><?= $editing ? 'Update Product' : 'Add Product' ?>
                            </button>
                        </div>
                    </form>
                </div><!-- /card-body -->
            </div><!-- /card -->

        </div><!-- /col -->
    </div><!-- /row -->
</div><!-- /container -->

<?php include 'admin_footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>