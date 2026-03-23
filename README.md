# E-Commerce Website

A fully functional e-commerce website built with PHP and MySQL.

## Features

- **Home Page**: Featured products display
- **Shop Page**: Browse all products with search and category filters
- **Shopping Cart**: Add, update, and remove items with real-time price calculation
- **User Authentication**: Login and registration system
- **Checkout**: Secure checkout with form validation
  - Name field: Alphabets only
  - Phone field: Valid Ethiopian phone number format
  - Email field: Valid email format
- **Payment Gateways**: Integration support for:
  - Chapa
  - Telebirr
  - CBE (Commercial Bank of Ethiopia)
  - Cash on Delivery
- **Admin Panel**: Complete admin dashboard to:
  - Manage products (Add, Edit, Delete)
  - Manage orders (View and update status)
  - View users
  - View statistics

## Installation

1. **Database Setup**:
   - Import the SQL file to create the database:
   ```sql
   mysql -u root -p < config/init.sql
   ```
   Or use phpMyAdmin to import `config/init.sql`

2. **Database Configuration**:
   - Edit `config/database.php` and update database credentials if needed:
   ```php
   $host = 'localhost';
   $dbname = 'ecommerce_db';
   $username = 'root';
   $password = '';
   ```

3. **Web Server**:
   - Place the project in your XAMPP `htdocs` folder
   - Access via: `http://localhost/ec/`

4. **Default Admin Login**:
   - Email: `admin@admin.com`
   - Password: `admin123`

## Project Structure

```
ec/
├── admin/              # Admin panel pages
│   ├── index.php      # Admin dashboard
│   ├── products.php   # Product management
│   ├── orders.php     # Order management
│   └── users.php      # User management
├── api/               # API endpoints
│   └── cart.php       # Cart operations
├── assets/            # Static files
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript files
│   └── images/        # Product images
├── config/            # Configuration files
│   ├── database.php   # Database connection
│   └── init.sql       # Database schema
├── includes/          # Shared PHP files
│   ├── functions.php  # Helper functions
│   ├── header.php     # Header template
│   └── footer.php     # Footer template
├── index.php          # Home page
├── shop.php           # Shop page
├── cart.php           # Shopping cart
├── checkout.php       # Checkout page
├── login.php          # Login page
├── register.php       # Registration page
└── profile.php        # User profile
```

## Features Details

### Form Validation
- **Name**: Only alphabets and spaces allowed
- **Phone**: Valid Ethiopian phone number format (+251XXXXXXXXX or 0XXXXXXXXX)
- **Email**: Standard email validation

### Payment Integration
The payment gateway integration is set up with placeholder functions. To integrate with real payment gateways:
1. Update `payment.php` with actual API calls
2. Add API credentials to `config/database.php` or a separate config file
3. Implement webhook handlers for payment callbacks

### Security Features
- Password hashing using bcrypt
- SQL injection prevention with prepared statements
- XSS protection with htmlspecialchars
- Session management for authentication

## Notes

- Product images should be placed in `assets/images/` directory
- Default placeholder image: `assets/images/placeholder.jpg`
- Make sure PHP sessions are enabled
- Ensure MySQL PDO extension is enabled


