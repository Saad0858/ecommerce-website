<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';
require_once __DIR__ . '/../backend/includes/get_sales_data.php';

require_admin();

$startDate = filter_input(INPUT_GET, 'start_date');
$endDate = filter_input(INPUT_GET, 'end_date');

$salesReport = get_sales_report($startDate, $endDate);
?>
<div class="admin-container">
    <h2>Sales Reports</h2>
    
    <form class="report-filters">
        <label>From: <input type="date" name="start_date" value="<?= $startDate ?>"></label>
        <label>To: <input type="date" name="end_date" value="<?= $endDate ?>"></label>
        <button type="submit" class="btn">Generate Report</button>
    </form>
    
    <table class="admin-table">
        <tr>
            <th>Date</th>
            <th>Orders</th>
            <th>Total Sales</th>
        </tr>
        <?php foreach ($salesReport as $day): ?>
        <tr>
            <td><?= date('M j, Y', strtotime($day['order_date'])) ?></td>
            <td><?= $day['order_count'] ?></td>
            <td>$<?= number_format($day['daily_total'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>