<?php
require_once '../config/session.php';
requireLogin();

$pageTitle = 'Payment Success - VegOut Café';
include '../includes/header.php';
?>

<main class="container">
    <div class="form-container" style="text-align:center;">
        <h2>Payment Successful!</h2>
        <p>Your payment for Order #<?= htmlspecialchars($_GET['order_id'] ?? '') ?> has been processed.</p>
        <p>Thank you for your purchase!</p>
        <a href="dashboard.php" class="btn">View My Orders</a>
        <a href="/vegout-cafe/index.php" class="btn">Continue Shopping</a>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
