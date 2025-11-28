<?php
require_once '../config/database.php';
require_once '../config/session.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$db = getDB();
$user_id = $_SESSION['user_id'];

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = (int)($_POST['product_id'] ?? 0);
    
    // Get or create cart
    $stmt = $db->prepare("SELECT cart_id FROM carts WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $cart = $stmt->get_result()->fetch_assoc();
    
    if (!$cart) {
        $stmt = $db->prepare("INSERT INTO carts (user_id) VALUES (?)");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $cart_id = $db->insert_id;
    } else {
        $cart_id = $cart['cart_id'];
    }
    
    if ($action === 'add' && $product_id > 0) {
        // Check if item exists
        $stmt = $db->prepare("SELECT quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->bind_param('ii', $cart_id, $product_id);
        $stmt->execute();
        $existing = $stmt->get_result()->fetch_assoc();
        
        if ($existing) {
            $new_qty = $existing['quantity'] + 1;
            $stmt = $db->prepare("UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND product_id = ?");
            $stmt->bind_param('iii', $new_qty, $cart_id, $product_id);
        } else {
            $stmt = $db->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, 1)");
            $stmt->bind_param('ii', $cart_id, $product_id);
        }
        $stmt->execute();
    }
    
    header('Location: cart.php');
    exit;
}

// Get cart items
$stmt = $db->prepare("
    SELECT c.cart_id FROM carts c WHERE c.user_id = ?
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$cart = $stmt->get_result()->fetch_assoc();

$cartItems = [];
$total = 0;

if ($cart) {
    $stmt = $db->prepare("
        SELECT ci.product_id, ci.quantity, p.name, p.price,
               (ci.quantity * p.price) as subtotal
        FROM cart_items ci 
        JOIN products p ON ci.product_id = p.product_id 
        WHERE ci.cart_id = ?
    ");
    $stmt->bind_param('i', $cart['cart_id']);
    $stmt->execute();
    $cartItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    foreach ($cartItems as $item) {
        $total += $item['subtotal'];
    }
}

$pageTitle = 'Cart - VegOut Café';
include '../includes/header.php';
?>

<main class="container">
    <h2>Shopping Cart</h2>
    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty. <a href="shop.php">Continue shopping</a></p>
    <?php else: ?>
        <div style="background:white;padding:2rem;border-radius:10px;">
            <?php foreach ($cartItems as $item): ?>
                <div style="display:flex;justify-content:space-between;margin-bottom:1rem;border-bottom:1px solid #ddd;padding-bottom:1rem;">
                    <div>
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p>$<?= number_format($item['price'], 2) ?> × <?= $item['quantity'] ?></p>
                    </div>
                    <div>
                        <strong>$<?= number_format($item['subtotal'], 2) ?></strong>
                    </div>
                </div>
            <?php endforeach; ?>
            <div style="text-align:right;margin-top:2rem;">
                <h3>Total: $<?= number_format($total, 2) ?></h3>
                <a href="checkout.php" class="btn">Proceed to Checkout</a>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
