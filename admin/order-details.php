<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    header("Location: ../index.php");
    exit;
}

$orderId = (int)($_GET['id'] ?? 0);

if ($orderId <= 0) {
    header("Location: orders.php");
    exit;
}

// Get order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: orders.php");
    exit;
}

// Get order items
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$orderId]);
$orderItems = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

    <main class="admin-page">
        <div class="container">
            <h1>Order Details</h1>
            
            <div class="admin-nav">
                <a href="index.php">Dashboard</a>
                <a href="products.php">Products</a>
                <a href="orders.php">Orders</a>
                <a href="users.php">Users</a>
            </div>
            
            <div class="order-details">
                <h2>Order #<?php echo htmlspecialchars($order['order_number']); ?></h2>
                
                <div class="order-info">
                    <h3>Customer Information</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                    <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></p>
                </div>
                
                <div class="order-info">
                    <h3>Order Information</h3>
                    <p><strong>Total Amount:</strong> ETB<?php echo number_format($order['total_amount'], 2); ?></p>
                    <p><strong>Payment Method:</strong> <?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></p>
                    <p><strong>Payment Status:</strong> <?php echo ucfirst($order['payment_status']); ?></p>
                    <p><strong>Order Status:</strong> <?php echo ucfirst($order['order_status']); ?></p>
                    <p><strong>Order Date:</strong> <?php echo date('Y-m-d H:i:s', strtotime($order['created_at'])); ?></p>
                </div>
                
                <div class="order-items">
                    <h3>Order Items</h3>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderItems as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>ETB<?php echo number_format($item['price'], 2); ?></td>
                                    <td>ETB<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong>Total:</strong></td>
                                <td><strong>ETB<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="order-actions">
                    <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
                </div>
            </div>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

