<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';
require_once __DIR__ . '/../backend/includes/get_orders.php';

require_admin();
?>
<div class="admin-container">
    <h2>Order Management</h2>
    
    <table class="admin-table">
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Order Date</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?= $order['id'] ?></td>
            <td><?= htmlspecialchars($order['shipping_name']) ?></td>
            <td><?= number_format($order['total_amount'], 2) ?></td>
            <td>
                <form action="../backend/update_order_status.php" method="POST">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <select name="status">
                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                    <button type="submit" class="btn-sm">Update</button>
                </form>
            </td>
            <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
            <td>
                <a href="order_details.php?id=<?= $order['id'] ?>">View</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>