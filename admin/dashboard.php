<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';
require_once __DIR__ . '/../backend/includes/get_sales_data.php';

require_admin();

$salesData = get_sales_summary();
$recentOrders = get_recent_orders(5);
?>
<div class="admin-container">
    <div class="dashboard-widgets">
        <div class="widget">
            <h3>Today's Sales</h3>
            <p>$<?= number_format($salesData['daily_sales'], 2) ?></p>
        </div>
        <div class="widget">
            <h3>Monthly Sales</h3>
            <p>$<?= number_format($salesData['monthly_sales'], 2) ?></p>
        </div>
        <div class="widget">
            <h3>Total Orders</h3>
            <p><?= $salesData['total_orders'] ?></p>
        </div>
    </div>
    
    <h3>Recent Orders</h3>
    <table class="admin-table">
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
        <?php foreach ($recentOrders as $order): ?>
        <tr>
            <td><?= $order['id'] ?></td>
            <td><?= htmlspecialchars($order['name']) ?></td>
            <td>$<?= number_format($order['total_amount'], 2) ?></td>
            <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>