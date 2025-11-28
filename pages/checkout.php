<?php
require_once '../config/database.php';
require_once '../config/session.php';
requireLogin();

$db = getDB();
$user_id = $_SESSION['user_id'];

// Get user's cart
$stmt = $db->prepare("SELECT cart_id FROM carts WHERE user_id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$cart = $stmt->get_result()->fetch_assoc();

if (!$cart) {
    header('Location: cart.php');
    exit;
}

$cart_id = $cart['cart_id'];

// Get cart items
$stmt = $db->prepare("
    SELECT ci.*, p.name, p.price 
    FROM cart_items ci 
    JOIN products p ON ci.product_id = p.product_id 
    WHERE ci.cart_id = ?
");
$stmt->bind_param('i', $cart_id);
$stmt->execute();
$cartItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (empty($cartItems)) {
    header('Location: cart.php');
    exit;
}

$total = 0;
foreach ($cartItems as &$item) {
    $item['subtotal'] = $item['price'] * $item['quantity'];
    $total += $item['subtotal'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db->begin_transaction();
    
    try {
        $stmt = $db->prepare("INSERT INTO orders (user_id, total_amount, order_status, payment_status) VALUES (?, ?, 'pending', 'pending')");
        $stmt->bind_param('id', $user_id, $total);
        $stmt->execute();
        $order_id = $db->insert_id;
        
        $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, unit_price, quantity, subtotal) VALUES (?, ?, ?, ?, ?)");
        foreach ($cartItems as $item) {
            $stmt->bind_param('iidid', $order_id, $item['product_id'], $item['price'], $item['quantity'], $item['subtotal']);
            $stmt->execute();
        }
        
        $stmt = $db->prepare("INSERT INTO order_shipping (order_id, full_name, phone, address_line1, city, state, postal_code, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssssss', $order_id, $_POST['full_name'], $_POST['phone'], $_POST['address'], $_POST['city'], $_POST['state'], $_POST['postal_code'], $_POST['country']);
        $stmt->execute();
        
        // Clear cart
        $stmt = $db->prepare("DELETE FROM cart_items WHERE cart_id = ?");
        $stmt->bind_param('i', $cart_id);
        $stmt->execute();
        
        $db->commit();
        header('Location: order-success.php?order_id=' . $order_id);
        exit;
    } catch (Exception $e) {
        $db->rollback();
        $error = 'Order failed. Please try again.';
    }
}

$pageTitle = 'Checkout - VegOut Café';
include '../includes/header.php';
?>

<main class="container">
    <h2>Checkout</h2>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;">
        <div class="form-container">
            <h3>Shipping Information</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" required>
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" required>
                </div>
                <div class="form-group">
                    <label>State</label>
                    <input type="text" name="state" required>
                </div>
                <div class="form-group">
                    <label>Postal Code</label>
                    <input type="text" name="postal_code" required>
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="country" required>
                </div>
                <button type="submit" class="btn">Place Order</button>
            </form>
        </div>
        <div style="background:white;padding:2rem;border-radius:10px;">
            <h3>Order Summary</h3>
            <?php foreach ($cartItems as $item): ?>
                <div style="margin-bottom:1rem;">
                    <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                    Qty: <?= $item['quantity'] ?> × $<?= number_format($item['price'], 2) ?> = $<?= number_format($item['subtotal'], 2) ?>
                </div>
            <?php endforeach; ?>
            <hr>
            <h3>Total: $<?= number_format($total, 2) ?></h3>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
