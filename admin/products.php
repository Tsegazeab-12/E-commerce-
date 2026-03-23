<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    header("Location: ../index.php");
    exit;
}

$message = '';
$error = '';

// Handle product actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $name = sanitize($_POST['name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $category = sanitize($_POST['category'] ?? '');
        $stock = (int)($_POST['stock'] ?? 0);
        $featured = isset($_POST['featured']) ? 1 : 0;
        $image = sanitize($_POST['image'] ?? 'placeholder.jpg');
        
        if (empty($name) || $price <= 0) {
            $error = 'Please fill in all required fields';
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, stock, featured, image) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $description, $price, $category, $stock, $featured, $image])) {
                $message = 'Product added successfully';
            } else {
                $error = 'Failed to add product';
            }
        }
    } elseif (isset($_POST['update_product'])) {
        $id = (int)($_POST['id'] ?? 0);
        $name = sanitize($_POST['name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $category = sanitize($_POST['category'] ?? '');
        $stock = (int)($_POST['stock'] ?? 0);
        $featured = isset($_POST['featured']) ? 1 : 0;
        $image = sanitize($_POST['image'] ?? 'placeholder.jpg');
        
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, 
                               stock = ?, featured = ?, image = ? WHERE id = ?");
        if ($stmt->execute([$name, $description, $price, $category, $stock, $featured, $image, $id])) {
            $message = 'Product updated successfully';
        } else {
            $error = 'Failed to update product';
        }
    } elseif (isset($_POST['delete_product'])) {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        if ($stmt->execute([$id])) {
            $message = 'Product deleted successfully';
        } else {
            $error = 'Failed to delete product';
        }
    }
}

// Get all products
$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

    <main class="admin-page">
        <div class="container">
            <h1>Manage Products</h1>
            
            <div class="admin-nav">
                <a href="index.php">Dashboard</a>
                <a href="products.php" class="active">Products</a>
                <a href="orders.php">Orders</a>
                <a href="users.php">Users</a>
            </div>
            
            <?php if ($message): ?>
                <div class="success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="admin-actions">
                <button onclick="showAddForm()" class="btn btn-primary">Add New Product</button>
            </div>
            
            <!-- Add/Edit Form -->
            <div id="productForm" class="product-form" style="display: none;">
                <h2 id="formTitle">Add Product</h2>
                <form method="POST" id="productFormElement">
                    <input type="hidden" name="id" id="productId">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="name" id="productName" required>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description" id="productDescription" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Price:</label>
                        <input type="number" step="0.01" name="price" id="productPrice" required>
                    </div>
                    <div class="form-group">
                        <label>Category:</label>
                        <input type="text" name="category" id="productCategory">
                    </div>
                    <div class="form-group">
                        <label>Stock:</label>
                        <input type="number" name="stock" id="productStock" value="0">
                    </div>
                    <div class="form-group">
                        <label>Image:</label>
                        <input type="text" name="image" id="productImage" value="placeholder.jpg">
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="featured" id="productFeatured"> Featured
                        </label>
                    </div>
                    <button type="submit" name="add_product" id="submitBtn" class="btn btn-primary">Add Product</button>
                    <button type="button" onclick="hideForm()" class="btn btn-secondary">Cancel</button>
                </form>
            </div>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>ETB<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td><?php echo $product['stock']; ?></td>
                            <td><?php echo $product['featured'] ? 'Yes' : 'No'; ?></td>
                            <td>
                                <button onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)" 
                                        class="btn btn-secondary">Edit</button>
                                <form method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" name="delete_product" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script>
        function showAddForm() {
            document.getElementById('productForm').style.display = 'block';
            document.getElementById('formTitle').textContent = 'Add Product';
            document.getElementById('productFormElement').reset();
            document.getElementById('productId').value = '';
            document.getElementById('submitBtn').name = 'add_product';
            document.getElementById('submitBtn').textContent = 'Add Product';
        }
        
        function hideForm() {
            document.getElementById('productForm').style.display = 'none';
        }
        
        function editProduct(product) {
            document.getElementById('productForm').style.display = 'block';
            document.getElementById('formTitle').textContent = 'Edit Product';
            document.getElementById('productId').value = product.id;
            document.getElementById('productName').value = product.name;
            document.getElementById('productDescription').value = product.description || '';
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productCategory').value = product.category || '';
            document.getElementById('productStock').value = product.stock;
            document.getElementById('productImage').value = product.image || 'placeholder.jpg';
            document.getElementById('productFeatured').checked = product.featured == 1;
            document.getElementById('submitBtn').name = 'update_product';
            document.getElementById('submitBtn').textContent = 'Update Product';
        }
    </script>
</body>
</html>

