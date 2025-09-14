<?php
require_once __DIR__ . '/../backend/includes/auth_check.php';
require_admin();

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="inventory_template.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Add CSV header
fputcsv($output, ['product_id', 'stock']);

// Add a sample row
fputcsv($output, ['1', '100']);
fputcsv($output, ['2', '50']);

// Close the output stream
fclose($output);
exit;