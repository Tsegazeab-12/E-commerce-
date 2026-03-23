<?php
$cartCount = getCartCount($pdo, getUserId(), getSessionId());
?>

<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
            <a href="index.php" style="
            color:#e5e7eb;
            text-decoration:none;
            font-size:20px;
            font-weight:700;
            letter-spacing:.4px;">
            Tech<span style="color:#60a5fa;">Store</span>
        </a>            </div>
            <nav class="nav">
                <a href="index.php">Home</a>
                <a href="shop.php">Shop</a>
                <a href="about.php">About</a>
                <a href="contact.php">Contact</a>
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="admin/index.php">Admin</a>
                    <?php endif; ?>
                    <a href="profile.php">Profile</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
                <a href="cart.php" class="cart-link">
                    Cart (<span id="cart-count"><?php echo $cartCount; ?></span>)
                </a>
            </nav>
        </div>
        <div class="search-bar">
            <form action="shop.php" method="GET">
                <input type="text" name="search" placeholder="Search products..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">Search</button>
            </form>
        </div>
    </div>
</header>

