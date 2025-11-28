<?php
require_once 'config/database.php';
$pageTitle = 'Home - VegOut Café';
include 'includes/header.php';
?>

<section class="hero" style="background: linear-gradient(rgba(47, 133, 90, 0.7), rgba(47, 133, 90, 0.7)), url('https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=1200') center/cover no-repeat; color: white; padding: 5rem 2rem;">
    <h1>Fresh Vegan Products Curated for You</h1>
    <p style="color: rgba(255, 255, 255, 0.95);">Browse high-quality vegan items from trusted sellers and enjoy a smooth, modern shopping experience.</p>
    <?php if (!isLoggedIn()): ?>
        <div style="margin-top: 2rem;">
            <a href="/vegout-cafe/pages/register.php" class="btn" style="text-decoration:none;margin-right:1rem;">Get Started</a>
            <a href="/vegout-cafe/pages/login.php" class="btn" style="text-decoration:none;background:#fff;color:var(--primary);border:2px solid var(--primary);">Sign In</a>
        </div>
    <?php endif; ?>
</section>

<main class="container">
    <h2>Why Choose VegOut Café?</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
        <div style="text-align: center; padding: 1.5rem;">
            <img src="https://images.unsplash.com/photo-1490818387583-1baba5e638af?w=300" alt="Fresh ingredients" style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px; margin-bottom: 1rem;">
            <h3>100% Plant-Based</h3>
            <p style="color: var(--text-light);">All our products are carefully sourced from certified vegan suppliers.</p>
        </div>
        <div style="text-align: center; padding: 1.5rem;">
            <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=300" alt="Organic produce" style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px; margin-bottom: 1rem;">
            <h3>Organic & Fresh</h3>
            <p style="color: var(--text-light);">We prioritize organic ingredients to bring you the healthiest options.</p>
        </div>
        <div style="text-align: center; padding: 1.5rem;">
            <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=300" alt="Fast delivery" style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px; margin-bottom: 1rem;">
            <h3>Fast Delivery</h3>
            <p style="color: var(--text-light);">Get your favorite vegan products delivered fresh to your doorstep.</p>
        </div>
    </div>

</main>

<?php include 'includes/footer.php'; ?>
