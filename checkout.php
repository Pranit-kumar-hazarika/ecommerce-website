<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "config/db.php";
include "includes/header.php";

if (!isset($_SESSION['user'])) {
    header("Location: user/login.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header("Location: cart_view.php");
    exit;
}

$total = 0;
foreach ($cart as $id => $qty) {
    $stmt = $conn->prepare("SELECT price FROM products WHERE product_id=?");
    $stmt->execute([(int)$id]);
    $p = $stmt->fetch();
    if ($p) {
        $total += $p['price'] * $qty;
    }
}
$tax = $total * 0.1;
$grand_total = $total + $tax;
?>

<div class="max-w-2xl mx-auto px-8 py-24 pt-32">
  <div class="bg-white rounded-2xl shadow-lg p-8">
    <h2 class="text-3xl font-bold text-primary mb-8">Checkout</h2>
    
    <div class="bg-surface-container-low p-6 rounded-xl mb-8">
      <h3 class="text-xl font-bold text-primary mb-6">Order Summary</h3>
      <div class="space-y-4">
        <div class="flex justify-between text-on-surface">
          <span>Subtotal:</span>
          <span>₹<?= number_format($total) ?></span>
        </div>
        <div class="flex justify-between text-on-surface">
          <span>Tax (10%):</span>
          <span>₹<?= number_format($tax) ?></span>
        </div>
        <div class="flex justify-between text-on-surface">
          <span>Shipping:</span>
          <span class="text-green-600 font-bold">Free</span>
        </div>
        <div class="border-t border-on-surface-variant pt-4 mt-4 flex justify-between">
          <strong class="text-lg">Grand Total:</strong>
          <strong class="text-2xl text-primary">₹<?= number_format($grand_total) ?></strong>
        </div>
      </div>
    </div>

    <div class="mb-8">
      <h3 class="text-xl font-bold text-primary mb-4">Payment Method</h3>
      <div class="border-2 border-primary p-4 rounded-xl">
        <div class="flex items-center gap-3">
          <input class="w-5 h-5" type="radio" name="payment" id="cod" value="cod" checked>
          <label for="cod" class="cursor-pointer">
            <strong class="text-lg">Cash on Delivery (COD)</strong><br>
            <span class="text-on-surface-variant text-sm">Pay when you receive your order</span>
          </label>
        </div>
      </div>
    </div>

    <form action="place_order.php" method="post" class="flex flex-col gap-4">
      <button type="submit" class="w-full bg-gradient-to-br from-primary to-primary-container text-on-primary px-8 py-4 rounded-xl font-bold text-lg hover:scale-105 transition-transform">
        <span class="material-symbols-outlined">check_circle</span> Place Order
      </button>
      <a href="cart_view.php" class="w-full bg-surface-container-highest text-primary px-8 py-4 rounded-xl font-bold text-lg text-center hover:bg-surface-container-high transition-colors">
        Back to Cart
      </a>
    </form>
  </div>
</div>

<?php include "includes/footer.php"; ?>
