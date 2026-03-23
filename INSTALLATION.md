# Installation Guide

## Step 1: Database Setup

1. Open phpMyAdmin (usually at `http://localhost/phpmyadmin`)

2. Create a new database or import the SQL file:
   - Click on "New" to create a database named `ecommerce_db`
   - Or click "Import" and select `config/init.sql` file
   - Click "Go" to execute

3. The SQL file will:
   - Create all necessary tables
   - Insert a default admin user (email: `admin@admin.com`, password: `admin123`)
   - Insert sample products

## Step 2: Configure Database Connection

Edit `config/database.php` if your MySQL credentials are different:

```php
$host = 'localhost';      
$dbname = 'ecommerce_db'; 
$username = 'root';      
$password = '';           
```

## Step 3: Set Up Product Images

1. Place product images in the `assets/images/` folder
2. Update product image names in the database to match your image files
3. Default images will show a placeholder if not found

## Step 4: Access the Website

1. Start XAMPP (Apache and MySQL)
2. Navigate to: `http://localhost/ec/`
3. You should see the home page

## Step 5: Admin Access

1. Go to: `http://localhost/ec/login.php`
2. Login with:
   - Email: `admin@admin.com`
   - Password: `admin123`
3. Access admin panel from the navigation menu

## Troubleshooting

### Database Connection Error
- Make sure MySQL is running in XAMPP
- Check database credentials in `config/database.php`
- Verify database `ecommerce_db` exists

### Images Not Showing
- Check that `assets/images/` folder exists
- Verify image file names match database entries
- Check file permissions

### Session Issues
- Make sure PHP sessions are enabled
- Check `php.ini` for `session.save_path` setting

### 404 Errors
- Verify `.htaccess` file exists
- Check Apache `mod_rewrite` is enabled
- Verify file paths are correct

## Next Steps

1. **Customize Products**: Add your own products via admin panel
2. **Payment Integration**: Update `payment.php` with real payment gateway APIs
3. **Styling**: Customize `assets/css/style.css` to match your brand
4. **Security**: Change default admin password immediately

## Payment Gateway Integration

To integrate real payment gateways:

1. **Chapa**: 
   - Get API credentials from Chapa
   - Update `payment.php` with Chapa API calls

2. **Telebirr**:
   - Get API credentials from Telebirr
   - Update `payment.php` with Telebirr API calls

3. **CBE**:
   - Get API credentials from CBE
   - Update `payment.php` with CBE API calls

4. **Cash on Delivery**:
   - Already implemented (no API needed)

## Support

For issues or questions, check the code comments or refer to the README.md file.

