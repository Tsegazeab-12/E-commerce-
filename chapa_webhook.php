<?php
require_once 'config/database.php';
require_once 'config/chapa.php';

$payload = json_decode(file_get_contents("php://input"), true);

if (!isset($payload['tx_ref'])) {
    http_response_code(400);
    exit;
}

$tx_ref = $payload['tx_ref'];

$ch = curl_init(CHAPA_BASE_URL . "/transaction/verify/$tx_ref");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . CHAPA_SECRET_KEY
    ]
]);

$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if ($response['status'] === 'success' && $response['data']['status'] === 'success') {
    $stmt = $pdo->prepare("UPDATE orders SET payment_status='completed' WHERE tx_ref=?");
    $stmt->execute([$tx_ref]);
}

http_response_code(200);
