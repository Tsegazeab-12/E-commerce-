<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'add') {
    $productId = (int)($_POST['product_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 1);
    
    if ($productId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product']);
        exit;
    }
    
    // Check product exists and has stock
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    
    $userId = getUserId();
    $sessionId = getSessionId();
    
    // Check if item already in cart
    if ($userId) {
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE session_id = ? AND product_id = ?");
        $stmt->execute([$sessionId, $productId]);
    }
    
    $existing = $stmt->fetch();
    
    if ($existing) {
        $newQuantity = $existing['quantity'] + $quantity;
        if ($newQuantity > $product['stock']) {
            echo json_encode(['success' => false, 'message' => 'Not enough stock']);
            exit;
        }
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$newQuantity, $existing['id']]);
    } else {
        if ($quantity > $product['stock']) {
            echo json_encode(['success' => false, 'message' => 'Not enough stock']);
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity, session_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $productId, $quantity, $userId ? null : $sessionId]);
    }
    
    $cartCount = getCartCount($pdo, $userId, $sessionId);
    echo json_encode(['success' => true, 'message' => 'Added to cart', 'cart_count' => $cartCount]);
    
} elseif ($action === 'update') {
    $cartId = (int)($_POST['cart_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 1);
    
    if ($cartId <= 0 || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }
    
    $userId = getUserId();
    $sessionId = getSessionId();
    
    // Verify cart item belongs to user
    if ($userId) {
        $stmt = $pdo->prepare("SELECT c.*, p.stock FROM cart c JOIN products p ON c.product_id = p.id WHERE c.id = ? AND c.user_id = ?");
        $stmt->execute([$cartId, $userId]);
    } else {
        $stmt = $pdo->prepare("SELECT c.*, p.stock FROM cart c JOIN products p ON c.product_id = p.id WHERE c.id = ? AND c.session_id = ?");
        $stmt->execute([$cartId, $sessionId]);
    }
    
    $item = $stmt->fetch();
    
    if (!$item) {
        echo json_encode(['success' => false, 'message' => 'Item not found']);
        exit;
    }
    
    if ($quantity > $item['stock']) {
        echo json_encode(['success' => false, 'message' => 'Not enough stock']);
        exit;
    }
    
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->execute([$quantity, $cartId]);
    
    echo json_encode(['success' => true, 'message' => 'Cart updated']);
    
} elseif ($action === 'remove') {
    $cartId = (int)($_POST['cart_id'] ?? 0);
    
    if ($cartId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid cart ID']);
        exit;
    }
    
    $userId = getUserId();
    $sessionId = getSessionId();
    
    // Verify cart item belongs to user
    if ($userId) {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $stmt->execute([$cartId, $userId]);
    } else {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND session_id = ?");
        $stmt->execute([$cartId, $sessionId]);
    }
    
    echo json_encode(['success' => true, 'message' => 'Item removed']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>

