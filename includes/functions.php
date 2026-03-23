<?php
session_start();

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Get current user ID
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Get session ID for cart
function getSessionId() {
    if (!isset($_SESSION['session_id'])) {
        $_SESSION['session_id'] = session_id();
    }
    return $_SESSION['session_id'];
}

// Sanitize input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate phone (Ethiopian phone numbers)
function validatePhone($phone) {
    // Remove spaces, dashes, and parentheses
    $phone = preg_replace('/[\s\-\(\)]/', '', $phone);
    // Check if it's a valid Ethiopian phone number (9 or 10 digits, may start with +251 or 0)
    return preg_match('/^(\+251|0)?[79]\d{8}$/', $phone);
}

// Validate name (alphabets only)
function validateName($name) {
    return preg_match('/^[a-zA-Z\s]+$/', $name);
}

// Format phone number
function formatPhone($phone) {
    $phone = preg_replace('/[\s\-\(\)]/', '', $phone);
    if (strpos($phone, '+251') === 0) {
        return $phone;
    } elseif (strpos($phone, '0') === 0) {
        return '+251' . substr($phone, 1);
    } else {
        return '+251' . $phone;
    }
}

// Generate order number
function generateOrderNumber() {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

// Calculate cart total
function calculateCartTotal($pdo, $userId = null, $sessionId = null) {
    $total = 0;
    
    if ($userId) {
        $stmt = $pdo->prepare("SELECT c.*, p.price FROM cart c 
                               JOIN products p ON c.product_id = p.id 
                               WHERE c.user_id = ?");
        $stmt->execute([$userId]);
    } else {
        $stmt = $pdo->prepare("SELECT c.*, p.price FROM cart c 
                               JOIN products p ON c.product_id = p.id 
                               WHERE c.session_id = ?");
        $stmt->execute([$sessionId]);
    }
    
    $items = $stmt->fetchAll();
    foreach ($items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    return $total;
}

// Get cart items count
function getCartCount($pdo, $userId = null, $sessionId = null) {
    if ($userId) {
        $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);
    } else {
        $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart WHERE session_id = ?");
        $stmt->execute([$sessionId]);
    }
    
    $result = $stmt->fetch();
    return $result['total'] ?? 0;
}
?>

