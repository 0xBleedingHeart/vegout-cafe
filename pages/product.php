<?php
require_once '../config/database.php';

$id = $_GET['id'] ?? 0;
$db = getDB();
$stmt = $db->prepare("SELECT p.*, pm.media_url FROM products p LEFT JOIN product_media pm ON p.product_id = pm.product_id WHERE p.product_id = ? AND p.is_active = 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header('Location: /vegout-cafe/pages/shop.php');
    exit;
}

$pageTitle = $product['name'] . ' - VegOut Café';
include '../includes/header.php';
?>

<main class="container">
    <div style="background:white;padding:2rem;border-radius:10px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;">
            <div>
                <img src="<?= htmlspecialchars($product['media_url'] ?: '/vegout-cafe/assets/placeholder.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width:100%;border-radius:10px;">
            </div>
            <div>
                <h1><?= htmlspecialchars($product['name']) ?></h1>
                <p class="product-price" style="font-size:2rem;margin:1rem 0;">$<?= number_format($product['price'], 2) ?></p>
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                <p>Stock: <?= $product['stock_qty'] ?> available</p>
                <form method="POST" action="cart.php">
                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                    <input type="hidden" name="action" value="add">
                    <button type="submit" class="btn">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
