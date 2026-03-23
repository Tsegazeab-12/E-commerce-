<?php
require_once 'config/database.php';
require_once 'config/chapa.php';

$tx_ref = $_GET['tx_ref'] ?? '';

if (!$tx_ref) {
    die("Invalid transaction reference");
}

// Verify with Chapa
$ch = curl_init(CHAPA_BASE_URL . "/transaction/verify/$tx_ref");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . CHAPA_SECRET_KEY
    ]
]);

$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if ($response && $response['status'] === 'success' && $response['data']['status'] === 'success') {

    $stmt = $pdo->prepare("UPDATE orders SET payment_status='completed' WHERE tx_ref=?");
    $stmt->execute([$tx_ref]);

    header("Location: order-success.php?order=" . $response['data']['tx_ref']);
    exit;
}

die("Payment verification failed");
