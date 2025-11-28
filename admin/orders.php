<?php
require_once '../config/database.php';
require_once '../config/session.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$db = getDB();

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $stmt = $db->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt->bind_param('si', $status, $order_id);
    $stmt->execute();
    $stmt->close();
    header('Location: orders.php');
    exit;
}

$orders_result = $db->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.user_id ORDER BY o.created_at DESC");
$orders = $orders_result ? $orders_result->fetch_all(MYSQLI_ASSOC) : [];

$pageTitle = 'Manage Orders - Admin';
include '../includes/header.php';
?>

<div class="container" style="min-height: calc(100vh - 200px);">
    <h2>Manage Orders</h2>
    <a href="dashboard.php" class="btn">Back to Dashboard</a>
    
    <div style="background:white;padding:2rem;border-radius:10px;margin-top:2rem;">
        <?php if (empty($orders)): ?>
            <div style="text-align:center;padding:3rem;color:var(--text-light);">
                <h3>No Orders Yet</h3>
                <p>Orders will appear here once customers start placing them.</p>
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
                    <th style="padding:0.5rem;text-align:left;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:0.5rem;">#<?= $order['order_id'] ?></td>
                    <td style="padding:0.5rem;"><?= htmlspecialchars($order['username']) ?></td>
                    <td style="padding:0.5rem;">$<?= number_format($order['total_amount'], 2) ?></td>
                    <td style="padding:0.5rem;"><?= htmlspecialchars($order['order_status']) ?></td>
                    <td style="padding:0.5rem;"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                    <td style="padding:0.5rem;">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                            <select name="status">
                                <option value="pending" <?= $order['order_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="paid" <?= $order['order_status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                                <option value="shipped" <?= $order['order_status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="completed" <?= $order['order_status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= $order['order_status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
