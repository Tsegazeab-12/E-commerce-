<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$orderNumber = $_GET['order'] ?? '';

if (empty($orderNumber)) {
    header("Location: index.php");
    exit;
}

// Get order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ? AND user_id = ?");
$stmt->execute([$orderNumber, getUserId()]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: index.php");
    exit;
}

// Get order items
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$order['id']]);
$orderItems = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - E-Commerce Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="order-success-page">
        <div class="container">
            <div class="success-message">
                <h1>Order Placed Successfully!</h1>
                <p>Thank you for your purchase.</p>
            </div>
            
            <div class="order-details">
                <h2>Order Details</h2>
                <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
                <p><strong>Total Amount:</strong> ETB<?php echo number_format($order['total_amount'], 2); ?></p>
                <p><strong>Payment Method:</strong> <?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></p>
                <p><strong>Order Status:</strong> <?php echo ucfirst($order['order_status']); ?></p>
                
                <h3>Items:</h3>
                <ul>
                    <?php foreach ($orderItems as $item): ?>
                        <li><?php echo htmlspecialchars($item['product_name']); ?> - 
                            Quantity: <?php echo $item['quantity']; ?> - 
                            ETB<?php echo number_format($item['price'], 2); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="order-actions">
                <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                <a href="profile.php" class="btn btn-secondary">View Orders</a>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>

