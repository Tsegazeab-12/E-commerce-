<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'config/chapa.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$orderNumber   = $_GET['order'] ?? '';
$paymentMethod = $_GET['method'] ?? '';

if (!$orderNumber || !$paymentMethod) {
    header("Location: index.php");
    exit;
}

// Fetch order
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number=? AND user_id=?");
$stmt->execute([$orderNumber, getUserId()]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: index.php");
    exit;
}

/* ===============================
   CHAPA PAYMENT INITIALIZATION
================================ */
if ($paymentMethod === 'chapa' && isset($_POST['process_payment'])) {

    $tx_ref = 'TX-' . uniqid();

    // save tx_ref
    $stmt = $pdo->prepare("UPDATE orders SET tx_ref=? WHERE id=?");
    $stmt->execute([$tx_ref, $order['id']]);

    $data = [
        "amount" => $order['total_amount'],
        "currency" => "ETB",
        "email" => $order['customer_email'],
        "first_name" => $order['customer_name'],
        "tx_ref" => $tx_ref,
    
        // USER stays on receipt until clicking Done
        "return_url" => "http://localhost/ec/payment-complete.php",
    
        // BACKEND verification (silent, no redirect)
        "callback_url" => "http://localhost/ec/chapa_webhook.php"
    ];
    

    $ch = curl_init(CHAPA_BASE_URL . "/transaction/initialize");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . CHAPA_SECRET_KEY,
            "Content-Type: application/json"
        ]
    ]);

    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if ($response && $response['status'] === 'success') {
        header("Location: " . $response['data']['checkout_url']);
        exit;
    }

    die("Chapa initialization failed");
}

/* ===============================
   TELEBIRR & CBE (SIMULATED)
================================ */
if (($paymentMethod === 'telebirr' || $paymentMethod === 'cbe') && isset($_POST['process_payment'])) {
    $stmt = $pdo->prepare("UPDATE orders SET payment_status='pending' WHERE id=?");
    $stmt->execute([$order['id']]);
    header("Location: order-success.php?order=$orderNumber");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="payment-page">
<div class="container">

<h1>Payment</h1>

<div class="payment-info">
    <h2>Order #: <?= htmlspecialchars($orderNumber) ?></h2>
    <p>Total: <strong>ETB <?= number_format($order['total_amount'], 2) ?></strong></p>
    <p>Method: <strong><?= ucfirst($paymentMethod) ?></strong></p>
</div>

<div class="payment-form">

<?php if ($paymentMethod === 'chapa'): ?>

    <h3>Chapa Payment</h3>
    <p>You will be redirected to Chapa secure payment.</p>
    <form method="POST">
        <button class="btn btn-primary" name="process_payment">
            Proceed with Chapa
        </button>
    </form>

<?php elseif ($paymentMethod === 'telebirr'): ?>

    <h3>Telebirr Payment</h3>
    <p>Dial <strong>*127#</strong> → Pay Merchant → Code <strong>123456</strong></p>
    <form method="POST">
        <button class="btn btn-primary" name="process_payment">
            I Have Paid
        </button>
    </form>

<?php elseif ($paymentMethod === 'cbe'): ?>

    <h3>CBE Bank Transfer</h3>
    <p>
        Account Name: <strong>E-Commerce Store</strong><br>
        Account Number: <strong>1000XXXXXX</strong>
    </p>
    <form method="POST">
        <button class="btn btn-primary" name="process_payment">
            Confirm Transfer
        </button>
    </form>

<?php endif; ?>

</div>

<a href="checkout.php">← Back to Checkout</a>

</div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
