# VegOut Café - E-Commerce Platform

A full-featured PHP-based e-commerce web application for vegan products, built with a focus on simplicity, security, and user experience.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Database Schema](#database-schema)
- [Project Structure](#project-structure)
- [User Roles](#user-roles)
- [Core Functionality](#core-functionality)
- [Configuration](#configuration)
- [Usage Guide](#usage-guide)
- [Security Features](#security-features)
- [Troubleshooting](#troubleshooting)
- [Future Enhancements](#future-enhancements)
- [License](#license)

---

## 🌱 Overview

VegOut Café is a modern e-commerce platform designed specifically for vegan products. The application provides a seamless shopping experience with features including user authentication, product browsing, shopping cart management, secure checkout, and order tracking.

**Live Demo:** `http://localhost/vegout-cafe/`

---

## ✨ Features

### Customer Features
- **User Registration & Authentication** - Secure account creation with password hashing
- **Product Catalog** - Browse vegan products organized by categories
- **Product Search** - Find products quickly with search functionality
- **Shopping Cart** - Session-based cart management
- **Secure Checkout** - Multi-step checkout process with shipping information
- **Payment Processing** - Support for multiple payment methods (Card, Wallet, Cash on Delivery)
- **Order History** - View past orders and track order status
- **User Dashboard** - Manage profile and view order history

### Admin Features
- **Admin Dashboard** - Overview of platform statistics
- **User Management** - View and manage user accounts
- **Product Management** - Add, edit, and manage product listings
- **Order Management** - View and update order statuses
- **Payment Tracking** - Monitor payment transactions and statuses

### Seller Features
- **Product Listings** - Manage product inventory
- **Order Fulfillment** - Process customer orders

---

## 🛠 Technology Stack

- **Backend:** PHP 8.2.12
- **Database:** MySQL (MariaDB 10.4.32)
- **Server:** Apache (XAMPP)
- **Frontend:** HTML5, CSS3, JavaScript
- **Session Management:** PHP Sessions
- **Security:** Password hashing with bcrypt, SQL injection prevention

---

## 💻 System Requirements

- **PHP:** Version 8.0 or higher
- **MySQL/MariaDB:** Version 5.7 or higher
- **Apache:** Version 2.4 or higher
- **XAMPP:** Version 8.0+ (recommended) or equivalent LAMP/WAMP stack
- **Browser:** Modern web browser (Chrome, Firefox, Safari, Edge)

---

## 🚀 Installation

### Step 1: Clone or Download the Project

```bash
# Clone the repository
git clone <repository-url>

# Or download and extract to XAMPP htdocs folder
# Path: C:\xampp\htdocs\vegout-cafe (Windows)
# Path: /opt/lampp/htdocs/vegout-cafe (Linux)
```

### Step 2: Start XAMPP Services

1. Open XAMPP Control Panel
2. Start **Apache** server
3. Start **MySQL** database

### Step 3: Create Database

**Option A: Using phpMyAdmin**
1. Navigate to `http://localhost/phpmyadmin`
2. Create a new database named `vegout_cafe`
3. Import the SQL file:
   - Click on the `vegout_cafe` database
   - Go to the **Import** tab
   - Choose file: `vegout_cafe.sql`
   - Click **Go**

**Option B: Using MySQL Command Line**
```bash
mysql -u root -p
CREATE DATABASE vegout_cafe;
USE vegout_cafe;
SOURCE /path/to/vegout_cafe.sql;
EXIT;
```

### Step 4: Configure Database Connection

Edit `config/database.php` if your database credentials differ:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Your MySQL password
define('DB_NAME', 'vegout_cafe');
```

### Step 5: Access the Application

Open your browser and navigate to:
```
http://localhost/vegout-cafe/
```

---

## 🗄 Database Schema

### Tables Overview

| Table | Description |
|-------|-------------|
| `users` | User accounts (admin, seller, customer) |
| `categories` | Product categories with hierarchical support |
| `products` | Product listings with pricing and inventory |
| `carts` | Shopping cart sessions |
| `cart_items` | Items in shopping carts |
| `orders` | Customer orders with status tracking |
| `order_items` | Individual items in orders |
| `order_shipping` | Shipping addresses for orders |
| `payments` | Payment transactions and status |

### Key Relationships

```
users (1) ──→ (N) products (seller_id)
users (1) ──→ (N) carts (user_id)
users (1) ──→ (N) orders (user_id)
categories (1) ──→ (N) products (category_id)
carts (1) ──→ (N) cart_items (cart_id)
orders (1) ──→ (N) order_items (order_id)
orders (1) ──→ (1) order_shipping (order_id)
orders (1) ──→ (N) payments (order_id)
products (1) ──→ (N) cart_items (product_id)
products (1) ──→ (N) order_items (product_id)
```

### Database Enums

**User Roles:**
- `admin` - Full system access
- `seller` - Product management
- `customer` - Shopping and orders

**Order Status:**
- `pending` - Order placed, awaiting payment
- `paid` - Payment confirmed
- `shipped` - Order dispatched
- `completed` - Order delivered
- `cancelled` - Order cancelled
- `refunded` - Payment refunded

**Payment Status:**
- `pending` - Payment initiated
- `paid` - Payment successful
- `failed` - Payment failed
- `refunded` - Payment refunded

**Payment Methods:**
- `card` - Credit/Debit card
- `wallet` - Digital wallet
- `cash_on_delivery` - COD

---

## 📁 Project Structure

```
vegout-cafe/
├── admin/                      # Admin panel
│   ├── dashboard.php          # Admin dashboard
│   ├── users.php              # User management
│   ├── products.php           # Product management
│   ├── orders.php             # Order management
│   └── payments.php           # Payment tracking
│
├── assets/                     # Static assets
│   ├── css/
│   │   └── style.css          # Main stylesheet
│   ├── img/                   # Product images
│   └── js/                    # JavaScript files
│
├── config/                     # Configuration files
│   ├── database.php           # Database connection
│   └── session.php            # Session management
│
├── includes/                   # Reusable components
│   ├── header.php             # Common header
│   └── footer.php             # Common footer
│
├── pages/                      # Application pages
│   ├── login.php              # User login
│   ├── register.php           # User registration
│   ├── shop.php               # Product listing
│   ├── product.php            # Product details
│   ├── cart.php               # Shopping cart
│   ├── checkout.php           # Checkout process
│   ├── payment.php            # Payment processing
│   ├── payment-success.php    # Payment confirmation
│   ├── dashboard.php          # User dashboard
│   ├── order-success.php      # Order confirmation
│   └── logout.php             # User logout
│
├── index.php                   # Homepage
├── index.html                  # Static homepage template
├── setup_db.php               # Database setup script
├── vegout_cafe.sql            # Database dump
└── README.md                   # Documentation
```

---

## 👥 User Roles

### 1. Admin
- **Username:** `admin`
- **Email:** `admin@vegout.com`
- **Password:** `admin123` (default, change after first login)
- **Capabilities:**
  - Full system access
  - User management
  - Product oversight
  - Order management
  - Payment tracking
  - System configuration

### 2. Seller
- **Default Account:** `vegstore`
- **Capabilities:**
  - Add/edit products
  - Manage inventory
  - View orders
  - Update order status

### 3. Customer
- **Registration:** Open to public
- **Capabilities:**
  - Browse products
  - Add to cart
  - Place orders
  - Track orders
  - Manage profile

---

## 🔧 Core Functionality

### Authentication System

**Registration:**
- Email validation
- Password hashing (bcrypt)
- Duplicate email/username prevention
- Automatic role assignment (customer)

**Login:**
- Session-based authentication
- Secure password verification
- Role-based redirects

**Session Management:**
- Automatic session initialization
- Login state checking
- Protected routes

### Shopping Cart

- **Session-based storage** - No login required to browse
- **Add/Remove items** - Dynamic cart updates
- **Quantity management** - Adjust item quantities
- **Price calculation** - Real-time total updates
- **Persistent cart** - Cart saved across sessions

### Checkout Process

1. **Cart Review** - Verify items and quantities
2. **Shipping Information** - Enter delivery address
3. **Payment Method** - Select payment option
4. **Order Confirmation** - Review and place order
5. **Payment Processing** - Complete transaction
6. **Order Success** - Confirmation page with order details

### Order Management

**Customer View:**
- Order history
- Order status tracking
- Order details
- Reorder functionality

**Admin View:**
- All orders overview
- Status updates
- Order cancellation
- Refund processing

### Payment Processing

**Supported Methods:**
- Credit/Debit Card
- Digital Wallet
- Cash on Delivery

**Transaction Tracking:**
- Unique transaction reference
- Payment status monitoring
- Refund management

---

## ⚙ Configuration

### Database Configuration

File: `config/database.php`

```php
define('DB_HOST', 'localhost');     // Database host
define('DB_USER', 'root');          // Database username
define('DB_PASS', '');              // Database password
define('DB_NAME', 'vegout_cafe');   // Database name
```

### Session Configuration

File: `config/session.php`

- Session auto-start enabled
- Helper functions for authentication
- Login requirement enforcement

### Application Settings

**Base URL:** Adjust paths in navigation links if deploying to subdirectory

**Image Paths:** Product images stored in `assets/img/`

**Timezone:** Set in PHP configuration (default: server timezone)

---

## 📖 Usage Guide

### For Customers

1. **Browse Products**
   - Visit homepage or shop page
   - View product categories
   - Search for specific items

2. **Add to Cart**
   - Click on product
   - Select quantity
   - Add to cart

3. **Checkout**
   - Review cart
   - Login or register
   - Enter shipping details
   - Select payment method
   - Confirm order

4. **Track Orders**
   - Login to dashboard
   - View order history
   - Check order status

### For Admins

1. **Access Admin Panel**
   - Login with admin credentials
   - Navigate to `/admin/`

2. **Manage Users**
   - View all users
   - Update user status
   - Manage roles

3. **Manage Products**
   - Add new products
   - Edit existing products
   - Update inventory
   - Activate/deactivate products

4. **Process Orders**
   - View all orders
   - Update order status
   - Process refunds
   - Track payments

---

## 🔒 Security Features

### Implemented Security Measures

1. **Password Security**
   - Bcrypt hashing (cost factor: 10)
   - No plain text storage
   - Secure password verification

2. **SQL Injection Prevention**
   - Prepared statements
   - Parameterized queries
   - Input sanitization

3. **Session Security**
   - Session-based authentication
   - Login state verification
   - Protected routes

4. **Access Control**
   - Role-based permissions
   - Admin-only routes
   - User-specific data access

5. **Data Validation**
   - Email format validation
   - Required field checks
   - Data type enforcement

### Security Best Practices

- Change default admin password immediately
- Use strong passwords (min 8 characters)
- Keep PHP and MySQL updated
- Enable HTTPS in production
- Regular database backups
- Monitor error logs

---

## 🐛 Troubleshooting

### Common Issues

**Issue: Database connection failed**
```
Solution:
1. Verify MySQL is running in XAMPP
2. Check database credentials in config/database.php
3. Ensure database 'vegout_cafe' exists
4. Verify user has proper permissions
```

**Issue: Page not found (404)**
```
Solution:
1. Check project is in htdocs/vegout-cafe/
2. Verify Apache is running
3. Clear browser cache
4. Check file paths in navigation links
```

**Issue: Session not persisting**
```
Solution:
1. Check session.php is included
2. Verify session_start() is called
3. Check PHP session configuration
4. Clear browser cookies
```

**Issue: Images not loading**
```
Solution:
1. Verify images exist in assets/img/
2. Check file permissions
3. Verify image paths in code
4. Use correct file extensions
```

**Issue: Cannot login**
```
Solution:
1. Verify user exists in database
2. Check password is correct
3. Ensure password_verify() is working
4. Check session configuration
```

### Debug Mode

Enable error reporting for development:

```php
// Add to top of index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

**Note:** Disable in production!

---

## 🚧 Future Enhancements

### Planned Features

- [ ] Product reviews and ratings
- [ ] Wishlist functionality
- [ ] Advanced search filters
- [ ] Email notifications
- [ ] Order tracking with shipping integration
- [ ] Coupon and discount system
- [ ] Multi-language support
- [ ] Mobile responsive design improvements
- [ ] Product image gallery
- [ ] Social media integration
- [ ] Newsletter subscription
- [ ] Live chat support
- [ ] Analytics dashboard
- [ ] Export reports (PDF, CSV)
- [ ] API for mobile app integration

### Technical Improvements

- [ ] Implement MVC architecture
- [ ] Add AJAX for dynamic updates
- [ ] Implement caching system
- [ ] Add unit tests
- [ ] Implement REST API
- [ ] Add payment gateway integration (Stripe, PayPal)
- [ ] Implement email service (PHPMailer)
- [ ] Add logging system
- [ ] Implement rate limiting
- [ ] Add CSRF protection
- [ ] Implement file upload validation
- [ ] Add database migration system

---

## 📝 Sample Data

### Pre-loaded Products

The database includes 12 sample vegan products:

1. Vegan Cheese Platter - $14.99
2. Buddha Bowl - $18.99
3. Coconut Yogurt Bowl - $6.99
4. Avocado Toast - $12.99
5. Plant-Based Burger - $16.99
6. Vegan Curry Bowl - $16.99
7. Green Smoothie - $9.99
8. Margherita Pizza - $13.99
9. Chia Pudding - $8.99
10. Veggie Tacos - $15.99
11. Fresh Green Juice - $11.99
12. Veggie Sushi Rolls - $21.99

### Categories

- **Snacks** - Healthy vegan snacks
- **Beverages** - Plant-based drinks
- **Meals** - Ready-to-eat vegan meals

---

## 🤝 Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

---

## 📄 License

This project is open-source and available for educational purposes.

---

## 📞 Support

For issues, questions, or suggestions:

- Create an issue in the repository
- Contact: admin@vegout.com

---

## 🙏 Acknowledgments

- Product images from Unsplash
- Icons and design inspiration from modern e-commerce platforms
- Built with ❤️ for the vegan community

---

**Last Updated:** November 29, 2025  
**Version:** 1.0.0  
**Status:** Active Development
