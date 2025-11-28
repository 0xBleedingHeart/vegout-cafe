<?php
require_once '../config/database.php';
require_once '../config/session.php';
requireLogin();

$db = getDB();
$order_id = $_GET['order_id'] ?? 0;

// Get order details
$stmt = $db->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->bind_param('ii', $order_id, $_SESSION['user_id']);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['payment_method'];
    $transaction_ref = 'TXN' . time() . rand(1000, 9999);
    
    $db->begin_transaction();
    try {
        // Insert payment record
        $stmt = $db->prepare("INSERT INTO payments (order_id, amount, method, status, transaction_ref, processed_at) VALUES (?, ?, ?, 'successful', ?, NOW())");
        $stmt->bind_param('idss', $order_id, $order['total_amount'], $method, $transaction_ref);
        $stmt->execute();
        
        // Update order status
        $stmt = $db->prepare("UPDATE orders SET payment_status = 'paid', order_status = 'paid' WHERE order_id = ?");
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        
        $db->commit();
        header('Location: payment-success.php?order_id=' . $order_id);
        exit;
    } catch (Exception $e) {
        $db->rollback();
        $error = 'Payment failed. Please try again.';
    }
}

$pageTitle = 'Payment - VegOut Café';
include '../includes/header.php';
?>

<main class="container">
    <h2>Payment</h2>
    
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;">
        <div class="form-container">
            <h3>Select Payment Method</h3>
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>
                        <input type="radio" name="payment_method" value="card" required checked>
                        Credit/Debit Card
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="radio" name="payment_method" value="wallet" required>
                        Digital Wallet
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="radio" name="payment_method" value="cash_on_delivery" required>
                        Cash on Delivery
                    </label>
                </div>
                
                <button type="submit" class="btn">Process Payment</button>
            </form>
        </div>
        
        <div style="background:white;padding:2rem;border-radius:10px;">
            <h3>Order Summary</h3>
            <p><strong>Order #<?= $order['order_id'] ?></strong></p>
            <p>Status: <?= ucfirst($order['order_status']) ?></p>
            <hr>
            <h3>Amount to Pay: $<?= number_format($order['total_amount'], 2) ?></h3>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
