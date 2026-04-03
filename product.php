<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "config/db.php";
include "includes/header.php";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
    header("Location: index.php");
    exit;
}
?>

<div class="max-w-7xl mx-auto px-8 py-24 pt-32">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
    <div class="rounded-xl overflow-hidden bg-surface-container-low">
      <img src="uploads/<?= htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($p['name']) ?>">
    </div>

    <div>
      <h2 class="text-4xl font-extrabold text-primary mb-6"><?= htmlspecialchars($p['name']) ?></h2>
      
      <div class="mb-8">
        <h3 class="text-3xl font-bold text-primary">₹<?= number_format($p['price']) ?></h3>
      </div>

      <div class="bg-surface-container-low p-6 rounded-xl mb-6">
        <?php if ($p['stock'] > 0): ?>
          <span class="inline-block px-4 py-2 bg-green-100 text-green-700 rounded-lg font-bold mb-2">In Stock</span>
          <p class="text-on-surface-variant">Available: <?= $p['stock'] ?> items</p>
        <?php else: ?>
          <span class="inline-block px-4 py-2 bg-red-100 text-red-700 rounded-lg font-bold">Out of Stock</span>
        <?php endif; ?>
      </div>

      <?php if (isset($p['description']) && !empty($p['description'])): ?>
        <p class="text-on-surface-variant mb-8 text-lg leading-relaxed"><?= htmlspecialchars($p['description']) ?></p>
      <?php endif; ?>

      <div class="flex flex-col gap-4">
        <?php if ($p['stock'] > 0): ?>
          <a href="cart.php?id=<?= $p['product_id'] ?>" class="w-full bg-gradient-to-br from-primary to-primary-container text-on-primary px-8 py-4 rounded-xl font-bold text-center hover:scale-105 transition-transform">
            <span class="material-symbols-outlined">shopping_cart</span> Add to Cart
          </a>
        <?php else: ?>
          <button class="w-full bg-surface-container text-on-surface px-8 py-4 rounded-xl font-bold text-center opacity-50 cursor-not-allowed">
            Out of Stock
          </button>
        <?php endif; ?>
        <a href="index.php" class="w-full bg-surface-container-highest text-primary px-8 py-4 rounded-xl font-bold text-center hover:bg-surface-container-high transition-colors">
          Continue Shopping
        </a>
      </div>
    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>
