<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php?redirect=checkout.php");
    exit;
}

$userId = getUserId();
$cartItems = [];
$total = 0;

/* -----------------------------
   Ethiopian Cities
----------------------------- */
$ethiopianCities = [
    "Addis Ababa",
    "Adama",
    "Bahir Dar",
    "Hawassa",
    "Mekelle",
    "Dire Dawa",
    "Jimma",
    "Gondar",
    "Dessie",
    "Harar",
    "Shashamane",
    "Nekemte",
    "Debre Markos",
    "Debre Birhan",
    "Wolaita Sodo",
    "Arba Minch",
    "Asella",
    "Ambo",
    "Hosaena",
    "Woldia"
];

/* -----------------------------
   Get Cart Items
----------------------------- */
$stmt = $pdo->prepare("
    SELECT c.*, p.name, p.price, p.image, p.stock
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll();
$total = calculateCartTotal($pdo, $userId);

if (empty($cartItems)) {
    header("Location: cart.php");
    exit;
}

$error = '';
$success = '';

/* -----------------------------
   Handle Checkout
----------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name   = sanitize($_POST['name'] ?? '');
    $email  = sanitize($_POST['email'] ?? '');
    $phone  = sanitize($_POST['phone'] ?? '');
    $city   = sanitize($_POST['city'] ?? '');
    $addressDetails = sanitize($_POST['address_details'] ?? '');
    $paymentMethod  = sanitize($_POST['payment_method'] ?? '');

    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($city) ||
        empty($paymentMethod)
    ) {
        $error = 'Please fill in all required fields';
    } elseif (!validateName($name)) {
        $error = 'Name should contain only alphabets';
    } elseif (!validateEmail($email)) {
        $error = 'Invalid email format';
    } elseif (!validatePhone($phone)) {
        $error = 'Invalid phone number format';
    } elseif (!in_array($city, $ethiopianCities)) {
        $error = 'Invalid city selected';
    } else {

        // Combine city + details into one address field
        $address = $city . (!empty($addressDetails) ? ' - ' . $addressDetails : '');

        $orderNumber = generateOrderNumber();
        $pdo->beginTransaction();

        try {
            // Create order
            $stmt = $pdo->prepare("
                INSERT INTO orders (
                    user_id, order_number, total_amount, payment_method,
                    customer_name, customer_email, customer_phone, customer_address
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $userId,
                $orderNumber,
                $total,
                $paymentMethod,
                $name,
                $email,
                formatPhone($phone),
                $address
            ]);

            $orderId = $pdo->lastInsertId();

            // Insert order items + update stock
            $itemStmt = $pdo->prepare("
                INSERT INTO order_items
                (order_id, product_id, product_name, quantity, price)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stockStmt = $pdo->prepare("
                UPDATE products SET stock = stock - ? WHERE id = ?
            ");

            foreach ($cartItems as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['name'],
                    $item['quantity'],
                    $item['price']
                ]);

                $stockStmt->execute([
                    $item['quantity'],
                    $item['product_id']
                ]);
            }

            // Clear cart
            $pdo->prepare("DELETE FROM cart WHERE user_id = ?")
                ->execute([$userId]);

            $pdo->commit();

            // Redirect
            if ($paymentMethod === 'cash_on_delivery') {
                header("Location: order-success.php?order=" . $orderNumber);
            } else {
                header("Location: payment.php?order=" . $orderNumber . "&method=" . $paymentMethod);
            }
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Order failed. Please try again.';
        }
    }
}

/* -----------------------------
   Get User Info
----------------------------- */
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - E-Commerce Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="checkout-page">
    <div class="container">
        <h1>Checkout</h1>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="checkout-content">

            <!-- Checkout Form -->
            <div class="checkout-form">
                <h2>Customer Information</h2>

                <form method="POST" id="checkoutForm">

                    <div class="form-group">
                        <label>Full Name:</label>
                        <input type="text" name="name"
                               value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>"
                               pattern="[A-Za-z\s]+" required>
                    </div>

                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email"
                               value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Phone Number:</label>
                        <input type="tel" name="phone"
                               value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                               pattern="[0-9+\-\s\(\)]+" required>
                    </div>

                    <!-- City Dropdown -->
                    <div class="form-group">
                        <label>City:</label>
                        <select name="city" required>
                            <option value="">Select City</option>
                            <?php foreach ($ethiopianCities as $city): ?>
                                <option value="<?php echo htmlspecialchars($city); ?>">
                                    <?php echo htmlspecialchars($city); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Address Details -->
                    <div class="form-group full-width">
                        <label>Address Details (Optional):</label>
                        <textarea name="address_details" rows="3"
                            placeholder="Street, house number, landmark..."></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label>Payment Method:</label>
                        <select name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash_on_delivery">Cash on Delivery</option>
                            <option value="chapa">Chapa</option>
                            <option value="telebirr">Telebirr</option>
                            <option value="cbe">CBE</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Place Order
                    </button>

                </form>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h2>Order Summary</h2>

                <?php foreach ($cartItems as $item): ?>
                    <div class="summary-item">
                        <span>
                            <?php echo htmlspecialchars($item['name']); ?>
                            x<?php echo $item['quantity']; ?>
                        </span>
                        <span>
                            ETB<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </span>
                    </div>
                <?php endforeach; ?>

                <div class="summary-total">
                    <strong>Total: ETB<?php echo number_format($total, 2); ?></strong>
                </div>
            </div>

        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script src="assets/js/main.js"></script>
<script>
document.getElementById('checkoutForm').addEventListener('submit', function(e) {

    const name  = document.querySelector('input[name="name"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const phone = document.querySelector('input[name="phone"]').value;
    const city  = document.querySelector('select[name="city"]').value;

    if (!/^[a-zA-Z\s]+$/.test(name)) {
        e.preventDefault();
        alert('Name should contain only alphabets');
        return;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email');
        return;
    }

    const phoneClean = phone.replace(/[\s\-\(\)]/g, '');
    if (!/^(\+251|0)?[79]\d{8}$/.test(phoneClean)) {
        e.preventDefault();
        alert('Please enter a valid Ethiopian phone number');
        return;
    }

    if (!city) {
        e.preventDefault();
        alert('Please select a delivery city');
        return;
    }
});
</script>

</body>
</html>
