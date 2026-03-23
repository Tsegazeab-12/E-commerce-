<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - Tech Store</title>
</head>

<body style="margin:0; font-family:Segoe UI, Tahoma, sans-serif; background:#0e1116; color:#e5e7eb;">

<!-- SIMPLE NAV -->
<!-- SIMPLE NAV -->
<header class="simple-nav">
    <div class="nav-container">

        <!-- LOGO -->
        <a href="index.php" class="logo">
            Tech<span>Store</span>
        </a>

        <!-- NAV LINKS -->
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="about.php" class="active">About</a>
            <a href="contact.php">Contact</a>

            <a href="cart.php" class="cart-btn">
                Cart
            </a>
        </nav>

    </div>
</header>
<style>
    /* =========================
   SIMPLE NAV BAR
========================= */
.simple-nav {
    background: #020617;
    border-bottom: 1px solid rgba(255,255,255,.08);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.nav-container {
    max-width: 1150px;
    margin: auto;
    padding: 14px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    color: #e5e7eb;
    text-decoration: none;
    font-size: 20px;
    font-weight: 700;
    letter-spacing: .4px;
}

.logo span {
    color: #60a5fa;
}

.nav-links {
    display: flex;
    gap: 22px;
    align-items: center;
}

.nav-links a {
    color: #cbd5f5;
    text-decoration: none;
    font-weight: 500;
    position: relative;
}

.nav-links a::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -6px;
    width: 0;
    height: 2px;
    background: #60a5fa;
    transition: width .3s ease;
}

.nav-links a:hover::after,
.nav-links a.active::after {
    width: 100%;
}

.cart-btn {
    padding: 8px 14px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border-radius: 10px;
    color: #fff !important;
    font-weight: 600;
}

</style>

<main>

<!-- HERO SECTION -->
<section style="
    background:linear-gradient(140deg, #0f172a, #020617, #111827);
    padding:120px 0;
    text-align:center;
    color:#e5e7eb;
    border-bottom:1px solid rgba(255,255,255,.08);">

    <h1 style="font-size:44px; letter-spacing:.5px; margin-bottom:10px;">
        Powering The Future of Technology
    </h1>

    <p style="max-width:780px; margin:0 auto; font-size:18px; line-height:1.7; opacity:.9;">
        From gaming rigs to smart devices — we bring next-gen tech closer to you with quality, service,
        and innovation at the core.
    </p>
</section>

<!-- OUR STORY -->
<section style="max-width:1150px; margin:70px auto; padding:0 20px;">

<div style="
    background:#020617;
    border:1px solid rgba(255,255,255,.08);
    border-radius:16px;
    padding:30px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:30px;
    box-shadow:0 25px 70px rgba(0,0,0,.45);">

    <div>
        <h2 style="font-size:26px; margin-bottom:12px; color:#a5b4fc;">
            Our Mission
        </h2>

        <p style="line-height:1.8; margin-bottom:12px; opacity:.9;">
            We launched Tech Store with a bold vision — to create a reliable source for premium technology,
            built around trust, performance, and great value.
        </p>

        <p style="line-height:1.8; opacity:.9;">
            Every product we ship is hand-selected, tested, and backed with dedicated support —
            because tech should feel exciting, effortless, and empowering.
        </p>

        <a href="shop.php" style="
            display:inline-block;
            margin-top:18px;
            padding:12px 24px;
            background:linear-gradient(135deg, #4f46e5, #06b6d4);
            color:#fff;
            border-radius:10px;
            text-decoration:none;
            font-weight:600;
            box-shadow:0 12px 25px rgba(79,70,229,.35);">
            Explore Tech Products →
        </a>
    </div>

    <div style="border-radius:14px; overflow:hidden; background:#0b0f1c;">
        <img src="assets/images/about.jpg" alt="Tech Store" style="width:100%; display:block;">
    </div>

</div>
</section>

<!-- TECH VALUE CARDS -->
<section style="padding:50px 0; background:#0b0f1c; border-top:1px solid rgba(255,255,255,.06);">

<div style="max-width:1150px; margin:0 auto; padding:0 20px;">

    <h2 style="text-align:center; margin-bottom:10px; color:#a5b4fc;">
        Why Tech Enthusiasts Choose Us
    </h2>

    <p style="text-align:center; max-width:740px; margin:0 auto 30px; opacity:.85;">
        Engineered for performance — trusted by gamers, developers, and creators.
    </p>

    <div style="
        display:grid;
        grid-template-columns:repeat(3, 1fr);
        gap:22px;">

        <div style="
            background:#020617;
            border:1px solid rgba(255,255,255,.08);
            border-radius:14px;
            padding:20px;
            text-align:center;
            box-shadow:0 15px 35px rgba(0,0,0,.35);">
            <h3 style="margin-bottom:6px; color:#67e8f9;">Quality Hardware</h3>
            <p style="opacity:.9;">Carefully curated devices built for speed, durability, and real-world performance.</p>
        </div>

        <div style="
            background:#020617;
            border:1px solid rgba(255,255,255,.08);
            border-radius:14px;
            padding:20px;
            text-align:center;
            box-shadow:0 15px 35px rgba(0,0,0,.35);">
            <h3 style="margin-bottom:6px; color:#a5b4fc;">Secure & Fast Delivery</h3>
            <p style="opacity:.9;">Protected packaging, verified suppliers, and real-time tracking.</p>
        </div>

        <div style="
            background:#020617;
            border:1px solid rgba(255,255,255,.08);
            border-radius:14px;
            padding:20px;
            text-align:center;
            box-shadow:0 15px 35px rgba(0,0,0,.35);">
            <h3 style="margin-bottom:6px; color:#34d399;">Expert Support</h3>
            <p style="opacity:.9;">We help you choose the right gear — and support you after purchase.</p>
        </div>

    </div>
</div>
</section>

<!-- STATS / CREDIBILITY -->
<section style="padding:60px 0; background:#020617; border-top:1px solid rgba(255,255,255,.06);">

<div style="max-width:1150px; margin:0 auto; padding:0 20px;">
    
    <h2 style="text-align:center; margin-bottom:25px; color:#a5b4fc;">
        Trusted Tech — Built With Experience
    </h2>

    <div style="
        display:grid;
        grid-template-columns:repeat(4, 1fr);
        gap:20px;
        text-align:center;">

        <div>
            <h3 style="font-size:34px; margin-bottom:2px; color:#67e8f9;">12K+</h3>
            <p style="opacity:.85;">Happy Customers</p>
        </div>

        <div>
            <h3 style="font-size:34px; margin-bottom:2px; color:#a5b4fc;">1.8K+</h3>
            <p style="opacity:.85;">Tech Products</p>
        </div>

        <div>
            <h3 style="font-size:34px; margin-bottom:2px; color:#34d399;">98%</h3>
            <p style="opacity:.85;">Positive Reviews</p>
        </div>

        <div>
            <h3 style="font-size:34px; margin-bottom:2px; color:#facc15;">24/7</h3>
            <p style="opacity:.85;">Customer Support</p>
        </div>

    </div>

</div>
</section>

</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>
