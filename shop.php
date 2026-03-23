<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Build query
$where = [];
$params = [];

if (!empty($search)) {
    $where[] = "(name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($category)) {
    $where[] = "category = ?";
    $params[] = $category;
}

$whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// Get total count
$countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM products $whereClause");
$countStmt->execute($params);
$totalProducts = $countStmt->fetch()['total'];
$totalPages = ceil($totalProducts / $perPage);

// Get products
$perPage = (int)$perPage;
$offset  = (int)$offset;

$query = "
    SELECT * FROM products
    $whereClause
    ORDER BY created_at DESC
    LIMIT $perPage OFFSET $offset
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get categories
$categoriesStmt = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - E-Commerce Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="shop-page">
        <div class="container">
            <h1>Shop</h1>
            
            <!-- Filters -->
            <div class="filters">
                <form method="GET" action="shop.php">
                    <input type="text" name="search" placeholder="Search products..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <select name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" 
                                    <?php echo $category === $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Filter</button>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="products-grid">
                <?php if (empty($products)): ?>
                    <p>No products found.</p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="assets/images/<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             onerror="this.src='assets/images/placeholder.jpg'">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="price">ETB<?php echo number_format($product['price'], 2); ?></p>
                        <p class="stock">Stock: <?php echo $product['stock']; ?></p>
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary">View Details</a>
                        <button onclick="addToCart(<?php echo $product['id']; ?>)" 
                                class="btn btn-primary" 
                                <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                            Add to Cart
                        </button>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>" 
                       class="<?php echo $page === $i ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
</body>
</html>

