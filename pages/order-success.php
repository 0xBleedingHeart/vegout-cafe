<?php
require_once '../config/session.php';
requireLogin();

$pageTitle = 'Order Success - VegOut Café';
include '../includes/header.php';
?>

<main class="container">
    <div class="form-container" style="text-align:center;">
        <h2>Order Placed Successfully!</h2>
        <p>Your order #<?= htmlspecialchars($_GET['order_id'] ?? '') ?> has been received.</p>
        <p>We'll process it shortly and send you updates.</p>
        <a href="/vegout-cafe/pages/dashboard.php" class="btn">View My Orders</a>
        <a href="/vegout-cafe/index.php" class="btn">Continue Shopping</a>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
