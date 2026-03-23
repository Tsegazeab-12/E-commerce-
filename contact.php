<?php
session_start();
$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us | Your Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body { background: #f8f9fa; }
        .contact-header {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: #fff;
            padding: 60px 0;
        }
        .contact-card {
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0,0,0,.1);
        }
        .form-control { border-radius: 10px; }
        .btn-custom { border-radius: 30px; padding: 10px 30px; }
    </style>
</head>
<body>
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
<!-- Header -->
<section class="contact-header text-center">
    <div class="container">
        <h1 class="fw-bold">Contact Us</h1>
        <p class="lead mb-0">We’re here to help you</p>
    </div>
</section>

<section class="py-5">
<div class="container">

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="row g-4">

        <!-- Form -->
        <div class="col-lg-7">
            <div class="card contact-card p-4">
                <h4 class="fw-bold mb-4">Send a Message</h4>

                <form action="contact_action.php" method="POST" novalidate>

    <div class="row g-3">

        <!-- Name -->
        <div class="col-md-6">
            <input type="text"
                   name="name"
                   class="form-control"
                   placeholder="Your Name"
                   pattern="^[A-Za-z\s]{2,50}$"
                   title="Name must contain letters only"
                   required>
        </div>

        <!-- Email -->
        <div class="col-md-6">
            <input type="email"
                   name="email"
                   class="form-control"
                   placeholder="Your Email"
                   required>
        </div>

        <!-- Phone (Ethiopia) -->
        <div class="col-12">
            <input type="tel"
                   name="phone"
                   class="form-control"
                   placeholder="Phone (e.g. 09XXXXXXXX or +2519XXXXXXXX)"
                   pattern="^(\\+251|0)(9|7)[0-9]{8}$"
                   title="Enter a valid Ethiopian phone number"
                   required>
        </div>

        <!-- Subject -->
        <div class="col-12">
            <input type="text"
                   name="subject"
                   class="form-control"
                   minlength="3"
                   maxlength="100"
                   required>
        </div>

        <!-- Message -->
        <div class="col-12">
            <textarea name="message"
                      class="form-control"
                      rows="5"
                      minlength="10"
                      maxlength="1000"
                      required></textarea>
        </div>

        <div class="col-12 text-end">
            <button class="btn btn-primary btn-custom">
                Send Message
            </button>
        </div>

    </div>
</form>


            </div>
        </div>

        <!-- Info -->
        <div class="col-lg-5">
            <div class="card contact-card p-4 h-100">
                <h4 class="fw-bold mb-4">Contact Info</h4>

                <p><i class="fa fa-location-dot text-primary me-2"></i>Addis Ababa, Ethiopia</p>
                <p><i class="fa fa-phone text-primary me-2"></i>+251 937 671 871</p>
                <p><i class="fa fa-envelope text-primary me-2"></i>support@yourstore.com</p>

                <hr>
                <p class="fw-bold mb-1">Business Hours</p>
                <p>Mon–Fri: 9AM – 6PM</p>
            </div>
        </div>

    </div>

    <!-- Map -->
    <div class="mt-5">
        <div class="card contact-card overflow-hidden">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3786.752036587176!2d38.80518747483249!3d9.001100591059128!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x164b852528b8f94d%3A0xb932691d903f7674!2sUnity%20University%20Gerji!5e1!3m2!1sen!2set!4v1767338737708!5m2!1sen!2set" height="350" style="border:0;width:100%" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

</div>height="350" "
</section>

<footer class="bg-dark text-light text-center py-4">
    © <?= date('Y') ?> Your Store. All Rights Reserved.
</footer>

</body>
</html>
