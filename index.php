<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Get featured products
$stmt = $pdo->query("SELECT * FROM products WHERE featured = 1 ORDER BY created_at DESC LIMIT 6");
$featuredProducts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Store - Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
   
    <?php include 'includes/header.php'; ?>
    

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <h1>Welcome to Our Store</h1>
                <p>Discover amazing products at great prices</p>
                <a href="shop.php" class="btn btn-primary">Shop Now</a>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="featured-products">
            <div class="container">
                <h2>Featured Products</h2>
                <div class="products-grid">
                    <?php foreach ($featuredProducts as $product): ?>
                    <div class="product-card">
                    <?php 
$image = !empty($product['image']) 
    ? htmlspecialchars($product['image']) 
    : 'placeholder.jpg';
?>
<img 
    src="assets/images/<?php echo $image; ?>"
    alt="<?php echo htmlspecialchars($product['name']); ?>"
    class="product-img"
>

                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="price">ETB<?php echo number_format($product['price'], 2); ?></p>
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary">View Details</a>
                        <button onclick="addToCart(<?php echo $product['id']; ?>)" class="btn btn-primary">Add to Cart</button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
    <div id="toast" class="toast-notification"></div>
</body>
</html>

