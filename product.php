<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$productId = (int)($_GET['id'] ?? 0);

if ($productId <= 0) {
    header("Location: shop.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: shop.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - E-Commerce Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="product-page">
        <div class="container">
            <div class="product-detail">
                <div class="product-image">
                    <img src="assets/images/<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         onerror="this.src='assets/images/placeholder.jpg'">
                </div>
                <div class="product-info">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                    <p class="description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    <p class="stock">Stock: <?php echo $product['stock']; ?></p>
                    <p class="category">Category: <?php echo htmlspecialchars($product['category']); ?></p>
                    <div class="product-actions">
                        <input type="number" id="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>">
                        <button onclick="addToCart(<?php echo $product['id']; ?>, document.getElementById('quantity').value)" 
                                class="btn btn-primary"
                                <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>
</html>

