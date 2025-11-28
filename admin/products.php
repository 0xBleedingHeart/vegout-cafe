<?php
require_once '../config/database.php';
require_once '../config/session.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$db = getDB();

// Update product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $stmt = $db->prepare("UPDATE products SET name = ?, price = ?, stock_qty = ?, is_active = ? WHERE product_id = ?");
    $stmt->bind_param('sdiii', $name, $price, $stock, $is_active, $product_id);
    $stmt->execute();
    $stmt->close();
    header('Location: products.php');
    exit;
}

$products_result = $db->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.category_id ORDER BY p.product_id");
$products = $products_result ? $products_result->fetch_all(MYSQLI_ASSOC) : [];

$pageTitle = 'Manage Products - Admin';
include '../includes/header.php';
?>

<div class="container" style="min-height: calc(100vh - 200px);">
    <h2>Manage Products</h2>
    <a href="dashboard.php" class="btn">Back to Dashboard</a>
    
    <div style="background:white;padding:2rem;border-radius:10px;margin-top:2rem;">
        <?php if (empty($products)): ?>
            <div style="text-align:center;padding:3rem;color:var(--text-light);">
                <h3>No Products Yet</h3>
                <p>Products will appear here once they are added to the database.</p>
            </div>
        <?php else: ?>
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:2px solid #ddd;">
                    <th style="padding:0.5rem;text-align:left;">ID</th>
                    <th style="padding:0.5rem;text-align:left;">Name</th>
                    <th style="padding:0.5rem;text-align:left;">Category</th>
                    <th style="padding:0.5rem;text-align:left;">Price</th>
                    <th style="padding:0.5rem;text-align:left;">Stock</th>
                    <th style="padding:0.5rem;text-align:left;">Active</th>
                    <th style="padding:0.5rem;text-align:left;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr style="border-bottom:1px solid #eee;">
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                        <td style="padding:0.5rem;"><?= $product['product_id'] ?></td>
                        <td style="padding:0.5rem;"><input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" style="width:100%;"></td>
                        <td style="padding:0.5rem;"><?= htmlspecialchars($product['category_name']) ?></td>
                        <td style="padding:0.5rem;"><input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" style="width:80px;"></td>
                        <td style="padding:0.5rem;"><input type="number" name="stock" value="<?= $product['stock_qty'] ?>" style="width:60px;"></td>
                        <td style="padding:0.5rem;"><input type="checkbox" name="is_active" <?= $product['is_active'] ? 'checked' : '' ?>></td>
                        <td style="padding:0.5rem;"><button type="submit" class="btn">Update</button></td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
