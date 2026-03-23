<?php
require_once 'config/database.php';
require_once 'config/chapa.php';

$tx_ref = $_GET['tx_ref'] ?? null;
$status = "processing";

if ($tx_ref) {
    $ch = curl_init(CHAPA_BASE_URL . "/transaction/verify/$tx_ref");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . CHAPA_SECRET_KEY
        ]
    ]);

    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (
        $response &&
        $response['status'] === 'success' &&
        $response['data']['status'] === 'success'
    ) {
        $status = "success";
    } else {
        $status = "failed";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Status</title>
    <style>
        body {
            font-family: Arial;
            text-align: center;
            padding: 50px;
        }
        .success { color: green; }
        .failed { color: red; }
    </style>
</head>
<body>

<?php if ($status === "success"): ?>
    <h2 class="success">✅ Payment Successful</h2>
    <p>Your payment was completed successfully.</p>
<?php elseif ($status === "failed"): ?>
    <h2 class="failed">❌ Payment Failed</h2>
    <p>Please try again.</p>
<?php else: ?>
    <h2>⏳ Verifying Payment...</h2>
<?php endif; ?>

<a href="index.php">Go Home</a>

</body>
</html>
