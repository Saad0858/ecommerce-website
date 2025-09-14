<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/db_connection.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';

require_admin();

$success_count = 0;
$error_count = 0;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['inventory_file'])) {
    $file = $_FILES['inventory_file'];
    
    // Validate file
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($file_ext !== 'csv') {
        $error_message = "Please upload a CSV file.";
    } elseif ($file['error'] !== 0) {
        $error_message = "Error uploading file. Code: " . $file['error'];
    } else {
        // Process CSV file
        if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {
            // Start transaction
            $mysqli->begin_transaction();
            
            try {
                // Skip header row
                $header = fgetcsv($handle, 1000, ",");
                
                // Validate header format
                $expected_headers = ['product_id', 'stock'];
                if (count(array_intersect($header, $expected_headers)) !== count($expected_headers)) {
                    throw new Exception("CSV format is incorrect. Required columns: product_id, stock");
                }
                
                $row_number = 1; // Start after header
                
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $row_number++;
                    
                    // Map CSV columns to variables
                    $product_id = filter_var(trim($data[0]), FILTER_VALIDATE_INT);
                    $stock = filter_var(trim($data[1]), FILTER_VALIDATE_INT);
                    
                    // Validate data
                    if ($product_id === false || $stock === false || $stock < 0) {
                        $errors[] = "Row $row_number: Invalid product ID or stock value";
                        $error_count++;
                        continue;
                    }
                    
                    // Check if product exists
                    $check_stmt = $mysqli->prepare("SELECT id FROM products WHERE id = ?");
                    $check_stmt->bind_param("i", $product_id);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();
                    
                    if ($check_result->num_rows === 0) {
                        $errors[] = "Row $row_number: Product ID $product_id not found";
                        $error_count++;
                        continue;
                    }
                    
                    // Update product stock
                    $update_stmt = $mysqli->prepare("UPDATE products SET stock = ? WHERE id = ?");
                    $update_stmt->bind_param("ii", $stock, $product_id);
                    
                    if ($update_stmt->execute()) {
                        $success_count++;
                    } else {
                        $errors[] = "Row $row_number: Database error updating product ID $product_id";
                        $error_count++;
                    }
                }
                
                // If no successful updates or too many errors, rollback
                if ($success_count === 0 || ($error_count > $success_count)) {
                    throw new Exception("Too many errors occurred. No changes were made.");
                }
                
                $mysqli->commit();
                $success_message = "Inventory updated successfully. $success_count products updated.";
                
                if ($error_count > 0) {
                    $success_message .= " $error_count errors occurred.";
                }
                
            } catch (Exception $e) {
                $mysqli->rollback();
                $error_message = $e->getMessage();
            }
            
            fclose($handle);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Batch Inventory Update</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>
    
    <div class="container">
        <h1>Batch Inventory Update</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="error-details">
                <h3>Error Details:</h3>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <h2>Upload Inventory CSV</h2>
            <p>Upload a CSV file with product inventory updates. The file must include the following columns:</p>
            <ul>
                <li><strong>product_id</strong>: The ID of the product</li>
                <li><strong>stock</strong>: The new stock quantity</li>
            </ul>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Select CSV File:</label>
                    <input type="file" name="inventory_file" accept=".csv" required>
                </div>
                <button type="submit" class="btn">Upload and Process</button>
            </form>
            
            <div class="template-download">
                <h3>Download Template</h3>
                <p>You can download a template CSV file to get started:</p>
                <a href="download_template.php" class="btn btn-sm">Download Template</a>
            </div>
        </div>
        
        <div class="card">
            <h2>Instructions</h2>
            <ol>
                <li>Prepare your CSV file with product_id and stock columns</li>
                <li>Ensure all product IDs exist in the system</li>
                <li>Upload the file using the form above</li>
                <li>Review the results after processing</li>
            </ol>
            <p><strong>Note:</strong> The system will validate all entries before making any changes. If there are too many errors, no changes will be applied.</p>
        </div>
    </div>
    
    <?php include 'admin_footer.php'; ?>
</body>
</html>