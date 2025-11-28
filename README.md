# VegOut Café - E-Commerce Platform

A full-stack PHP e-commerce application for vegan products.

## Setup Instructions

1. **Database Setup**
   - Create a MySQL database named `vegout_cafe`
   - Import the `database.sql` file:
     ```bash
     mysql -u root -p vegout_cafe < database.sql
     ```

2. **Configuration**
   - Update database credentials in `config/database.php` if needed
   - Default: localhost, root, no password

3. **Start XAMPP**
   - Start Apache and MySQL services
   - Access the application at: `http://localhost/vegout-cafe/`

## Features

- User registration and authentication
- Product browsing and search
- Shopping cart (session-based)
- Checkout and order management
- User dashboard for order history
- Role-based access (admin, seller, customer)

## File Structure

```
vegout-cafe/
├── config/
│   ├── database.php      # Database connection
│   └── session.php       # Session management
├── includes/
│   ├── header.php        # Common header
│   └── footer.php        # Common footer
├── pages/
│   ├── login.php         # User login
│   ├── register.php      # User registration
│   ├── shop.php          # Product listing
│   ├── product.php       # Product details
│   ├── cart.php          # Shopping cart
│   ├── checkout.php      # Checkout process
│   ├── dashboard.php     # User dashboard
│   └── order-success.php # Order confirmation
├── assets/
│   └── css/
│       └── style.css     # Main stylesheet
├── index.php             # Homepage
└── database.sql          # Database schema
```

## Default Credentials

After importing the database, you'll need to register a new account to start using the application.

## Next Steps

- Add sample products to the database
- Upload product images to `assets/` folder
- Customize styling in `assets/css/style.css`
- Implement admin panel for product management
- Add payment gateway integration
