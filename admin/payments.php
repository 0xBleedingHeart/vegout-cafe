<?php
require_once '../config/database.php';
require_once '../config/session.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$db = getDB();

// Update payment status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_id'])) {
    $payment_id = $_POST['payment_id'];
    $status = $_POST['status'];
    
    $stmt = $db->prepare("UPDATE payments SET status = ? WHERE payment_id = ?");
    $stmt->bind_param('si', $status, $payment_id);
    $stmt->execute();
    $stmt->close();
    
    // Update order payment status if needed
    if ($status === 'successful') {
        $stmt = $db->prepare("UPDATE orders o JOIN payments p ON o.order_id = p.order_id SET o.payment_status = 'paid' WHERE p.payment_id = ?");
        $stmt->bind_param('i', $payment_id);
        $stmt->execute();
        $stmt->close();
    }
    
    header('Location: payments.php');
    exit;
}

$payments_result = $db->query("
    SELECT p.*, o.order_id, u.username 
    FROM payments p 
    JOIN orders o ON p.order_id = o.order_id 
    JOIN users u ON o.user_id = u.user_id 
    ORDER BY p.processed_at DESC
");
$payments = $payments_result ? $payments_result->fetch_all(MYSQLI_ASSOC) : [];

$pageTitle = 'Manage Payments - Admin';
include '../includes/header.php';
?>

<div class="container" style="min-height: calc(100vh - 200px);">
    <h2>Manage Payments</h2>
    <a href="dashboard.php" class="btn">Back to Dashboard</a>
    
    <div style="background:white;padding:2rem;border-radius:10px;margin-top:2rem;">
        <?php if (empty($payments)): ?>
            <div style="text-align:center;padding:3rem;color:var(--text-light);">
                <h3>No Payments Yet</h3>
                <p>Payment transactions will appear here once orders are placed.</p>
            </div>
        <?php else: ?>
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:2px solid #ddd;">
                    <th style="padding:0.5rem;text-align:left;">Payment ID</th>
                    <th style="padding:0.5rem;text-align:left;">Order ID</th>
                    <th style="padding:0.5rem;text-align:left;">Customer</th>
                    <th style="padding:0.5rem;text-align:left;">Amount</th>
                    <th style="padding:0.5rem;text-align:left;">Method</th>
                    <th style="padding:0.5rem;text-align:left;">Transaction Ref</th>
                    <th style="padding:0.5rem;text-align:left;">Status</th>
                    <th style="padding:0.5rem;text-align:left;">Date</th>
                    <th style="padding:0.5rem;text-align:left;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:0.5rem;">#<?= $payment['payment_id'] ?></td>
                    <td style="padding:0.5rem;">#<?= $payment['order_id'] ?></td>
                    <td style="padding:0.5rem;"><?= htmlspecialchars($payment['username']) ?></td>
                    <td style="padding:0.5rem;">$<?= number_format($payment['amount'], 2) ?></td>
                    <td style="padding:0.5rem;"><?= htmlspecialchars($payment['method']) ?></td>
                    <td style="padding:0.5rem;"><?= htmlspecialchars($payment['transaction_ref']) ?></td>
                    <td style="padding:0.5rem;"><?= htmlspecialchars($payment['status']) ?></td>
                    <td style="padding:0.5rem;"><?= date('M d, Y', strtotime($payment['processed_at'])) ?></td>
                    <td style="padding:0.5rem;">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="payment_id" value="<?= $payment['payment_id'] ?>">
                            <select name="status">
                                <option value="pending" <?= $payment['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="successful" <?= $payment['status'] === 'successful' ? 'selected' : '' ?>>Successful</option>
                                <option value="failed" <?= $payment['status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
                                <option value="refunded" <?= $payment['status'] === 'refunded' ? 'selected' : '' ?>>Refunded</option>
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
