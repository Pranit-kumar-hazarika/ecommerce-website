<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "config/db.php";
include "includes/header.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header("Location: cart_view.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<div class="container mt-5">
<h3>Your Cart</h3>

<?php foreach($cart as $id => $qty):
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
$stmt->execute([(int)$id]);
$p = $stmt->fetch();
if (!$p) continue;
$subtotal = $p['price'] * $qty;
$total += $subtotal;
?>

<div class="cart-item">
  <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
  <div>
    <h6><?= htmlspecialchars($p['name']) ?></h6>
    <p>Qty: <?= $qty ?></p>
    <strong>₹<?= number_format($subtotal) ?></strong>
  </div>
</div>

<?php endforeach; ?>

<h4>Total: ₹<?= number_format($total) ?></h4>
<a href="checkout.php" class="btn btn-dark">Checkout</a>
</div>

<?php include "includes/footer.php"; ?>
