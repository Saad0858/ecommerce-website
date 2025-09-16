<?php
/**
 * Sales-reporting helpers
 * included by admin/dashboard.php, reports.php, etc.
 */
require_once __DIR__ . '/db_connection.php';   // $mysqli

/* ----------  today's / monthly totals  ---------- */
function get_sales_summary(): array
{
    global $mysqli;

    $sql = "SELECT
               COALESCE(SUM(CASE WHEN DATE(created_at) = CURDATE() THEN total_amount END),0) AS daily_sales,
               COALESCE(SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN total_amount END),0) AS monthly_sales,
               COUNT(*) AS total_orders
            FROM orders";

    $res = $mysqli->query($sql);
    return $res->fetch_assoc() ?: ['daily_sales' => 0, 'monthly_sales' => 0, 'total_orders' => 0];
}

/* ----------  last N orders for widget  ---------- */
function get_recent_orders(int $limit = 5): array
{
    global $mysqli;

    $stmt = $mysqli->prepare(
        "SELECT o.id, u.name, o.total_amount, o.created_at
           FROM orders o
           JOIN users u ON o.user_id = u.id
          ORDER BY o.created_at DESC
          LIMIT ?"
    );
    $stmt->bind_param('i', $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/* ----------  filterable sales report  ---------- */
function get_sales_report(?string $startDate = null, ?string $endDate = null): array
{
    global $mysqli;

    $where = '';
    $types = '';
    $params = [];

    if ($startDate) {
        $where .= ' AND DATE(created_at) >= ?';
        $params[] = $startDate;
        $types .= 's';
    }
    if ($endDate) {
        $where .= ' AND DATE(created_at) <= ?';
        $params[] = $endDate;
        $types .= 's';
    }

    $sql = "SELECT
               DATE(created_at)        AS order_date,
               COUNT(*)                AS order_count,
               COALESCE(SUM(total_amount),0) AS daily_total
            FROM orders
            WHERE 1=1 {$where}
            GROUP BY DATE(created_at)
            ORDER BY order_date DESC";

    $stmt = $mysqli->prepare($sql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}