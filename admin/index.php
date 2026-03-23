<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    header("Location: ec/admin/index.php");
    exit;
}

// Get statistics
$stats = [
    'total_products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    'total_orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'total_users' => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn(),
    'total_revenue' => $pdo->query("SELECT SUM(total_amount) FROM orders WHERE payment_status = 'completed'")->fetchColumn() ?? 0
];

// Get recent orders
$recentOrders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - E-Commerce Store</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

    <main class="admin-dashboard">
        <div class="container">
            <h1>Admin Dashboard</h1>
            
            <div class="admin-nav">
                <a href="index.php" class="active">Dashboard</a>
                <a href="products.php">Products</a>
                <a href="orders.php">Orders</a>
                <a href="users.php">Users</a>
                <a href="admin_message.php">Messages</a>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <p><?php echo $stats['total_products']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <p><?php echo $stats['total_orders']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <p><?php echo $stats['total_users']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Revenue</h3>
                    <p>ETB<?php echo number_format($stats['total_revenue'], 2); ?></p>
                </div>
            </div>
            
            <div class="recent-orders">
                <h2>Recent Orders</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td>ETB<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo ucfirst($order['order_status']); ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></td>
                                <td><a href="order-details.php?id=<?php echo $order['id']; ?>">View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

