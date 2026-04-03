<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: user/login.php");
    exit;
}

$user_id = $_SESSION['user'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header("Location: cart_view.php");
    exit;
}

try {
    // Calculate total using prepared statements
    $total = 0;
    foreach ($cart as $id => $qty) {
        $stmt = $conn->prepare("SELECT price FROM products WHERE product_id=?");
        $stmt->execute([(int)$id]);
        $p = $stmt->fetch();
        if ($p) {
            $total += $p['price'] * $qty;
        }
    }

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders(user_id, total_amount, order_status) VALUES(?, ?, ?)");
    $stmt->execute([$user_id, $total, 'Pending']);
    $order_id = $conn->lastInsertId();

    // Insert order items
    $stmt = $conn->prepare("INSERT INTO order_items(order_id, product_id, quantity) VALUES(?, ?, ?)");
    foreach ($cart as $id => $qty) {
        $stmt->execute([$order_id, (int)$id, (int)$qty]);
    }

    // Clear cart
    unset($_SESSION['cart']);

    header("Location: user/profile.php");
    exit;
} catch (Exception $e) {
    die("Order placement failed: " . htmlspecialchars($e->getMessage()));
}
