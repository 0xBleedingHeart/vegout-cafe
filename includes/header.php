<?php
require_once __DIR__ . '/../config/session.php';
$user = getUser();
$is_admin = isset($user['role']) && $user['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $pageTitle ?? 'VegOut Café' ?></title>
    <link rel="stylesheet" href="/vegout-cafe/assets/css/style.css">
</head>
<body>
    <header>
        <div style="max-width: 900px; width: 100%; display: flex; justify-content: space-between; align-items: center;">
            <div class="logo"><a href="/vegout-cafe/index.php" style="color:white;text-decoration:none;">VegOut Café</a></div>
            <nav aria-label="Main navigation">
                <?php if ($is_admin): ?>
                    <a href="/vegout-cafe/admin/dashboard.php">Dashboard</a>
                    <a href="/vegout-cafe/admin/products.php">Products</a>
                    <a href="/vegout-cafe/admin/orders.php">Orders</a>
                    <a href="/vegout-cafe/admin/payments.php">Payments</a>
                    <a href="/vegout-cafe/admin/users.php">Users</a>
                    <a href="/vegout-cafe/pages/logout.php">Logout</a>
                <?php else: ?>
                    <a href="/vegout-cafe/index.php">Home</a>
                    <a href="/vegout-cafe/pages/shop.php">Shop</a>
                    <a href="/vegout-cafe/pages/cart.php">Cart</a>
                    <?php if ($user): ?>
                        <a href="/vegout-cafe/pages/dashboard.php"><?= htmlspecialchars($user['username'] ?? 'Account') ?></a>
                        <a href="/vegout-cafe/pages/logout.php">Logout</a>
                    <?php else: ?>
                        <a href="/vegout-cafe/pages/login.php">Login</a>
                        <a href="/vegout-cafe/pages/register.php">Register</a>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>
        </div>
    </header>
