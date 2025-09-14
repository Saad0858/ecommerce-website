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
        $stmt = $mysqli->prepare("UPDATE promo_codes SET code = ?, discount_percent = ?, 
                                min_order_amount = ?, is_active = ?, start_date = ?, 
                                end_date = ?, usage_limit = ? WHERE id = ?");
        $stmt->bind_param("sddiisii", $code, $discount, $min_amount, $is_active, 
                         $start_date, $end_date, $usage_limit, $id);
    } else {
        // Insert new promo code
        $stmt = $mysqli->prepare("INSERT INTO promo_codes (code, discount_percent, min_order_amount, 
                                is_active, start_date, end_date, usage_limit) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sddiisi", $code, $discount, $min_amount, $is_active, 
                         $start_date, $end_date, $usage_limit);
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

<!DOCTYPE html>
<html>
<head>
    <title>Promo Code Management</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>
    
    <div class="container">
        <h1><?= $editing ? 'Edit' : 'Add' ?> Promo Code</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form">
            <?php if ($editing): ?>
                <input type="hidden" name="id" value="<?= $promo['id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Promo Code:</label>
                <input type="text" name="code" value="<?= $editing ? $promo['code'] : '' ?>" required>
            </div>
            
            <div class="form-group">
                <label>Discount Percentage:</label>
                <input type="number" name="discount_percent" min="0" max="100" step="0.01" 
                       value="<?= $editing ? $promo['discount_percent'] : '' ?>" required>
            </div>
            
            <div class="form-group">
                <label>Minimum Order Amount:</label>
                <input type="number" name="min_order_amount" min="0" step="0.01" 
                       value="<?= $editing ? $promo['min_order_amount'] : '0' ?>">
            </div>
            
            <div class="form-group">
                <label>Active:</label>
                <input type="checkbox" name="is_active" <?= (!$editing || $promo['is_active']) ? 'checked' : '' ?>>
            </div>
            
            <div class="form-group">
                <label>Start Date:</label>
                <input type="date" name="start_date" value="<?= $editing ? $promo['start_date'] : date('Y-m-d') ?>" required>
            </div>
            
            <div class="form-group">
                <label>End Date:</label>
                <input type="date" name="end_date" value="<?= $editing ? $promo['end_date'] : date('Y-m-d', strtotime('+30 days')) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Usage Limit (blank for unlimited):</label>
                <input type="number" name="usage_limit" min="1" value="<?= $editing && $promo['usage_limit'] ? $promo['usage_limit'] : '' ?>">
            </div>
            
            <button type="submit" class="btn"><?= $editing ? 'Update' : 'Add' ?> Promo Code</button>
        </form>
        
        <h2>Existing Promo Codes</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Min Amount</th>
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
                        <td>$<?= number_format($code['min_order_amount'], 2) ?></td>
                        <td>
                            <span class="badge <?= $code['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                                <?= $code['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <?= date('M d, Y', strtotime($code['start_date'])) ?> - 
                            <?= date('M d, Y', strtotime($code['end_date'])) ?>
                        </td>
                        <td>
                            <?= $code['usage_count'] ?>
                            <?= $code['usage_limit'] ? '/' . $code['usage_limit'] : '' ?>
                        </td>
                        <td>
                            <a href="?edit=<?= $code['id'] ?>" class="btn btn-sm">Edit</a>
                            <a href="?delete=<?= $code['id'] ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this promo code?')">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($promo_codes) === 0): ?>
                    <tr>
                        <td colspan="7">No promo codes found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php include 'admin_footer.php'; ?>
</body>
</html>