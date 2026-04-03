<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "config/db.php";
include "includes/header.php";

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<div class="max-w-7xl mx-auto px-8 py-24 pt-32">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Cart Items -->
    <div class="lg:col-span-2">
      <h2 class="text-3xl font-bold text-primary mb-8"><span class="material-symbols-outlined">shopping_cart</span> Your Shopping Cart</h2>

      <?php if (empty($cart)): ?>
        <div class="bg-surface-container-low p-12 rounded-xl text-center">
          <h3 class="text-xl font-bold text-primary mb-2">Your cart is empty</h3>
          <p class="text-on-surface-variant mb-6">Start shopping and add items to your cart</p>
          <a href="index.php" class="inline-block bg-gradient-to-br from-primary to-primary-container text-on-primary px-8 py-3 rounded-xl font-bold hover:scale-105 transition-transform">
            Continue Shopping
          </a>
        </div>
      <?php else: ?>
        <div class="space-y-4">
          <?php foreach($cart as $id => $qty):
          $stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
          $stmt->execute([$id]);
          $p = $stmt->fetch();
          if (!$p) continue;
          $subtotal = $p['price'] * $qty;
          $total += $subtotal;
          ?>

          <div class="bg-white rounded-xl p-6 flex gap-4 items-start">
            <img src="uploads/<?= htmlspecialchars($p['image']) ?>" class="w-24 h-24 object-cover rounded-lg" alt="<?= htmlspecialchars($p['name']) ?>">
            <div class="flex-1">
              <h4 class="font-bold text-primary mb-1"><?= htmlspecialchars($p['name']) ?></h4>
              <p class="text-on-surface-variant text-sm mb-2">₹<?= number_format($p['price']) ?> each</p>
              <p class="font-bold">Qty: <?= $qty ?></p>
            </div>
            <div class="text-right">
              <p class="font-bold text-lg text-primary mb-3">₹<?= number_format($subtotal) ?></p>
              <a href="remove_cart.php?id=<?= $id ?>" class="inline-block bg-red-100 text-red-700 px-4 py-2 rounded-lg font-bold text-sm hover:bg-red-200 transition-colors">
                <span class="material-symbols-outlined">delete</span> Remove
              </a>
            </div>
          </div>

          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Order Summary -->
    <div class="lg:col-span-1">
      <div class="bg-white rounded-xl p-6 sticky top-28">
        <h3 class="text-xl font-bold text-primary mb-6">Order Summary</h3>

        <?php if (!empty($cart)): ?>
          <div class="space-y-4 mb-6">
            <div class="flex justify-between text-on-surface">
              <span>Subtotal:</span>
              <span class="font-bold">₹<?= number_format($total) ?></span>
            </div>
            <div class="flex justify-between text-on-surface">
              <span>Shipping:</span>
              <span class="text-green-600 font-bold">Free</span>
            </div>
            <div class="flex justify-between text-on-surface">
              <span>Tax (10%):</span>
              <span class="font-bold">₹<?= number_format($total * 0.1) ?></span>
            </div>
            <div class="border-t border-on-surface-variant pt-4 flex justify-between">
              <strong>Total:</strong>
              <strong class="text-lg text-primary">₹<?= number_format($total * 1.1) ?></strong>
            </div>
          </div>

          <?php if (isset($_SESSION['user'])): ?>
            <a href="checkout.php" class="w-full block bg-gradient-to-br from-primary to-primary-container text-on-primary px-6 py-3 rounded-xl font-bold text-center hover:scale-105 transition-transform mb-3">
              Proceed to Checkout
            </a>
          <?php else: ?>
            <a href="user/login.php" class="w-full block bg-gradient-to-br from-primary to-primary-container text-on-primary px-6 py-3 rounded-xl font-bold text-center hover:scale-105 transition-transform mb-3">
              Login to Checkout
            </a>
          <?php endif; ?>

          <a href="index.php" class="w-full block bg-surface-container-highest text-primary px-6 py-3 rounded-xl font-bold text-center hover:bg-surface-container-high transition-colors">
            Continue Shopping
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>
