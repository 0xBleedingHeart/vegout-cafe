<?php
require_once 'config/database.php';

try {
    $db = getDB();
    
    // Clear tables in correct order
    $db->query("SET FOREIGN_KEY_CHECKS = 0");
    $db->query("TRUNCATE TABLE cart_items");
    $db->query("TRUNCATE TABLE carts");
    $db->query("TRUNCATE TABLE order_items");
    $db->query("TRUNCATE TABLE order_shipping");
    $db->query("TRUNCATE TABLE orders");
    $db->query("TRUNCATE TABLE products");
    $db->query("TRUNCATE TABLE categories");
    $db->query("TRUNCATE TABLE users");
    $db->query("SET FOREIGN_KEY_CHECKS = 1");
    
    // Insert categories
    $db->query("INSERT INTO categories VALUES 
    (1, NULL, 'Snacks', 'Healthy vegan snacks'),
    (2, NULL, 'Beverages', 'Plant-based drinks'),
    (3, NULL, 'Meals', 'Ready-to-eat vegan meals')");
    
    // Insert seller
    $db->query("INSERT INTO users VALUES 
    (1, 'seller', 'vegstore', 'seller@vegout.com', 'password123', 'VegOut Store', NULL, 'active', NOW(), NOW())");
    
    // Insert products
    $db->query("INSERT INTO products VALUES 
    (1, 1, 1, 'Vegan Cheese Platter', 'Delicious vegan cheese platter', 14.99, 50, 1, NOW(), NOW()),
    (2, 1, 3, 'Buddha Bowl', 'Nutritious buddha bowl', 18.99, 50, 1, NOW(), NOW()),
    (3, 1, 1, 'Coconut Yogurt Bowl', 'Creamy coconut yogurt', 6.99, 50, 1, NOW(), NOW()),
    (4, 1, 1, 'Avocado Toast', 'Fresh avocado on toast', 12.99, 50, 1, NOW(), NOW()),
    (5, 1, 3, 'Plant-Based Burger', 'Tasty plant burger', 16.99, 50, 1, NOW(), NOW()),
    (6, 1, 3, 'Vegan Curry Bowl', 'Spicy curry bowl', 16.99, 50, 1, NOW(), NOW()),
    (7, 1, 2, 'Green Smoothie', 'Healthy green smoothie', 9.99, 50, 1, NOW(), NOW()),
    (8, 1, 3, 'Margherita Pizza', 'Classic vegan pizza', 13.99, 50, 1, NOW(), NOW()),
    (9, 1, 1, 'Chia Pudding', 'Nutritious chia pudding', 8.99, 50, 1, NOW(), NOW()),
    (10, 1, 3, 'Veggie Tacos', 'Fresh veggie tacos', 15.99, 50, 1, NOW(), NOW()),
    (11, 1, 2, 'Fresh Green Juice', 'Cold pressed juice', 11.99, 50, 1, NOW(), NOW()),
    (12, 1, 3, 'Veggie Sushi Rolls', 'Fresh sushi rolls', 21.99, 50, 1, NOW(), NOW())");
    
    echo "Database setup completed successfully!";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
