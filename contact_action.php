<?php
session_start();
require_once 'config/database.php';

function clean($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

$name    = clean($_POST['name'] ?? '');
$email   = clean($_POST['email'] ?? '');
$phone   = clean($_POST['phone'] ?? '');
$subject = clean($_POST['subject'] ?? '');
$message = clean($_POST['message'] ?? '');

/* ================= VALIDATION ================= */

// Name: letters & spaces only
if (!preg_match('/^[A-Za-z\s]{2,50}$/', $name)) {
    $_SESSION['error'] = 'Name must contain letters only.';
    header('Location: contact.php'); exit;
}

// Email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email address.';
    header('Location: contact.php'); exit;
}

// Ethiopian phone numbers
if (!preg_match('/^(\+251|0)(9|7)[0-9]{8}$/', $phone)) {
    $_SESSION['error'] = 'Invalid Ethiopian phone number.';
    header('Location: contact.php'); exit;
}

// Subject length
if (strlen($subject) < 3 || strlen($subject) > 100) {
    $_SESSION['error'] = 'Subject must be between 3 and 100 characters.';
    header('Location: contact.php'); exit;
}

// Message length
if (strlen($message) < 10 || strlen($message) > 1000) {
    $_SESSION['error'] = 'Message must be between 10 and 1000 characters.';
    header('Location: contact.php'); exit;
}

/* ================= INSERT ================= */

$stmt = $pdo->prepare("
    INSERT INTO contact_messages (name, email, phone, subject, message)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([$name, $email, $phone, $subject, $message]);

$_SESSION['success'] = 'Message sent successfully!';
header('Location: contact.php');
exit;
