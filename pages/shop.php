<?php
require_once '../config/database.php';
$pageTitle = 'Shop - VegOut Café';
include '../includes/header.php';

// Hardcoded products for shop
$products = [
    ['product_id' => 1, 'name' => 'Vegan Cheese Platter', 'price' => 14.99, 'media_url' => '../assets/img/Cheese Platter.png'],
    ['product_id' => 2, 'name' => 'Buddha Bowl', 'price' => 18.99, 'media_url' => '../assets/img/buddha bowl.png'],
    ['product_id' => 3, 'name' => 'Coconut Yogurt Bowl', 'price' => 6.99, 'media_url' => '../assets/img/coconut yogurt bowl.png'],
    ['product_id' => 4, 'name' => 'Avocado Toast', 'price' => 12.99, 'media_url' => '../assets/img/avocado toast.png'],
    ['product_id' => 5, 'name' => 'Plant-Based Burger', 'price' => 16.99, 'media_url' => '../assets/img/plant-based burger.png'],
    ['product_id' => 6, 'name' => 'Vegan Curry Bowl', 'price' => 16.99, 'media_url' => '../assets/img/vegan curry bowl.png'],
    ['product_id' => 7, 'name' => 'Green Smoothie', 'price' => 9.99, 'media_url' => '../assets/img/matcha smoothie.png'],
    ['product_id' => 8, 'name' => 'Margherita Pizza', 'price' => 13.99, 'media_url' => '../assets/img/margherita pizza.png'],
    ['product_id' => 9, 'name' => 'Chia Pudding', 'price' => 8.99, 'media_url' => '../assets/img/chia pudding.png'],
    ['product_id' => 10, 'name' => 'Veggie Tacos', 'price' => 15.99, 'media_url' => '../assets/img/veggie tacos.png'],
    ['product_id' => 11, 'name' => 'Fresh Green Juice', 'price' => 11.99, 'media_url' => '../assets/img/green juice.png'],
    ['product_id' => 12, 'name' => 'Veggie Sushi Rolls', 'price' => 21.99, 'media_url' => '../assets/img/veggie sushi rolls.png']
];
?>

<main class="container">
    <h2>All Products</h2>
    <section class="products" aria-label="Products List">
        <?php foreach ($products as $product): ?>
            <article class="product-card">
                <a href="product.php?id=<?= $product['product_id'] ?>">
                    <img src="<?= htmlspecialchars($product['media_url'] ?: '/vegout-cafe/assets/placeholder.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
                </a>
                <div class="product-info">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="product-price">$<?= number_format($product['price'], 2) ?></p>
                    <form method="POST" action="cart.php">
                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                        <input type="hidden" name="action" value="add">
                        <button type="submit" class="btn">Add to Cart</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
