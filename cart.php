<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$userId = getUserId();
$sessionId = getSessionId();

// Get cart items
if ($userId) {
    $stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.image, p.stock 
                           FROM cart c 
                           JOIN products p ON c.product_id = p.id 
                           WHERE c.user_id = ?");
    $stmt->execute([$userId]);
} else {
    $stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.image, p.stock 
                           FROM cart c 
                           JOIN products p ON c.product_id = p.id 
                           WHERE c.session_id = ?");
    $stmt->execute([$sessionId]);
}

$cartItems = $stmt->fetchAll();
$total = calculateCartTotal($pdo, $userId, $sessionId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - E-Commerce Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="cart-page">
        <div class="container">
            <h1>Shopping Cart</h1>
            
            <?php if (empty($cartItems)): ?>
                <p>Your cart is empty. <a href="shop.php">Continue Shopping</a></p>
            <?php else: ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td>
                                <img src="assets/images/<?php echo htmlspecialchars($item['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>"
                                     onerror="this.src='assets/images/placeholder.jpg'">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </td>
                            <td>ETB<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <input type="number" 
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="1" 
                                       max="<?php echo $item['stock']; ?>"
                                       onchange="updateCart(<?php echo $item['id']; ?>, this.value)">
                            </td>
                            <td>ETB<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <button onclick="removeFromCart(<?php echo $item['id']; ?>)" class="btn btn-danger">Remove</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><strong>Total:</strong></td>
                            <td colspan="2"><strong>ETB<?php echo number_format($total, 2); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="cart-actions">
                    <a href="shop.php" class="btn btn-secondary">Continue Shopping</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
                    <?php else: ?>
                        <a href="login.php?redirect=checkout.php" class="btn btn-primary">Login to Checkout</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>
</html>

