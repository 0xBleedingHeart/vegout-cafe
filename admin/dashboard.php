<?php
require_once '../config/database.php';
require_once '../config/session.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$db = getDB();

// Get statistics
$users = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'")->fetch_assoc()['count'];
$products = $db->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$orders = $db->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$revenue = $db->query("SELECT SUM(total_amount) as total FROM orders WHERE order_status != 'cancelled'")->fetch_assoc()['total'] ?? 0;

// Get recent orders
$recent_orders_result = $db->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.user_id ORDER BY o.created_at DESC LIMIT 10");
$recent_orders = $recent_orders_result ? $recent_orders_result->fetch_all(MYSQLI_ASSOC) : [];

$pageTitle = 'Admin Dashboard - VegOut Café';
include '../includes/header.php';
?>

<div class="container" style="min-height: calc(100vh - 200px);">
    <h2>Admin Dashboard</h2>
    
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin:2rem 0;">
        <div style="background:white;padding:1.5rem;border-radius:10px;text-align:center;">
            <h3><?= $users ?></h3>
            <p>Customers</p>
        </div>
        <div style="background:white;padding:1.5rem;border-radius:10px;text-align:center;">
            <h3><?= $products ?></h3>
            <p>Products</p>
        </div>
        <div style="background:white;padding:1.5rem;border-radius:10px;text-align:center;">
            <h3><?= $orders ?></h3>
            <p>Orders</p>
        </div>
        <div style="background:white;padding:1.5rem;border-radius:10px;text-align:center;">
            <h3>$<?= number_format($revenue, 2) ?></h3>
            <p>Revenue</p>
        </div>
    </div>
    
    <div style="background:white;padding:2rem;border-radius:10px;margin-top:2rem;">
        <h3>Recent Orders</h3>
        <?php if (empty($recent_orders)): ?>
            <div style="text-align:center;padding:2rem;color:var(--text-light);">
                <p>No orders yet. Orders will appear here once customers start placing them.</p>
            </div>
        <?php else: ?>
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:2px solid #ddd;">
                    <th style="padding:0.5rem;text-align:left;">Order ID</th>
                    <th style="padding:0.5rem;text-align:left;">Customer</th>
                    <th style="padding:0.5rem;text-align:left;">Amount</th>
                    <th style="padding:0.5rem;text-align:left;">Status</th>
                    <th style="padding:0.5rem;text-align:left;">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_orders as $order): ?>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:0.5rem;">#<?= $order['order_id'] ?></td>
                    <td style="padding:0.5rem;"><?= htmlspecialchars($order['username']) ?></td>
                    <td style="padding:0.5rem;">$<?= number_format($order['total_amount'], 2) ?></td>
                    <td style="padding:0.5rem;"><?= htmlspecialchars($order['order_status']) ?></td>
                    <td style="padding:0.5rem;"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
