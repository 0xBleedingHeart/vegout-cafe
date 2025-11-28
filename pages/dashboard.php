<?php
require_once '../config/database.php';
require_once '../config/session.php';
requireLogin();

$db = getDB();
$stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Dashboard - VegOut Café';
include '../includes/header.php';
?>

<main class="container">
    <h2>My Orders</h2>
    <?php if (empty($orders)): ?>
        <p>You haven't placed any orders yet.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <?php
            // Get order items
            $stmt = $db->prepare("
                SELECT oi.*, p.name 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.product_id 
                WHERE oi.order_id = ?
            ");
            $stmt->bind_param('i', $order['order_id']);
            $stmt->execute();
            $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            
            // Get shipping info
            $stmt = $db->prepare("SELECT * FROM order_shipping WHERE order_id = ?");
            $stmt->bind_param('i', $order['order_id']);
            $stmt->execute();
            $shipping = $stmt->get_result()->fetch_assoc();
            ?>
            
            <div style="background:white;padding:2rem;border-radius:10px;margin-bottom:2rem;border:2px solid #ddd;">
                <div style="display:flex;justify-content:space-between;border-bottom:2px solid #ddd;padding-bottom:1rem;margin-bottom:1rem;">
                    <div>
                        <h3>Order #<?= $order['order_id'] ?></h3>
                        <p>Date: <?= date('F d, Y h:i A', strtotime($order['created_at'])) ?></p>
                    </div>
                    <div style="text-align:right;">
                        <p><strong>Status:</strong> <?= ucfirst($order['order_status']) ?></p>
                        <p><strong>Payment:</strong> <?= ucfirst($order['payment_status']) ?></p>
                        <?php if ($order['payment_status'] === 'pending'): ?>
                            <a href="payment.php?order_id=<?= $order['order_id'] ?>" class="btn" style="margin-top:0.5rem;">Pay Now</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div style="margin-bottom:1.5rem;">
                    <h4>Items Ordered:</h4>
                    <table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="border-bottom:1px solid #ddd;">
                                <th style="padding:0.5rem;text-align:left;">Product</th>
                                <th style="padding:0.5rem;text-align:center;">Quantity</th>
                                <th style="padding:0.5rem;text-align:right;">Price</th>
                                <th style="padding:0.5rem;text-align:right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr style="border-bottom:1px solid #eee;">
                                <td style="padding:0.5rem;"><?= htmlspecialchars($item['name']) ?></td>
                                <td style="padding:0.5rem;text-align:center;"><?= $item['quantity'] ?></td>
                                <td style="padding:0.5rem;text-align:right;">$<?= number_format($item['unit_price'], 2) ?></td>
                                <td style="padding:0.5rem;text-align:right;">$<?= number_format($item['subtotal'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="border-top:2px solid #ddd;">
                                <td colspan="3" style="padding:0.5rem;text-align:right;"><strong>Total:</strong></td>
                                <td style="padding:0.5rem;text-align:right;"><strong>$<?= number_format($order['total_amount'], 2) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <?php if ($shipping): ?>
                <div>
                    <h4>Shipping Address:</h4>
                    <p><?= htmlspecialchars($shipping['full_name']) ?><br>
                    <?= htmlspecialchars($shipping['address_line1']) ?><br>
                    <?php if ($shipping['address_line2']): ?>
                        <?= htmlspecialchars($shipping['address_line2']) ?><br>
                    <?php endif; ?>
                    <?= htmlspecialchars($shipping['city']) ?>, <?= htmlspecialchars($shipping['state']) ?> <?= htmlspecialchars($shipping['postal_code']) ?><br>
                    <?= htmlspecialchars($shipping['country']) ?><br>
                    Phone: <?= htmlspecialchars($shipping['phone']) ?></p>
                </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
