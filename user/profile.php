<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../config/db.php";
include "../includes/header.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id = (int)$_SESSION['user'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY order_id DESC");
$stmt->execute([$id]);
$orders = $stmt->fetchAll();
?>

<div class="container my-5">
  <div class="row">
    <div class="col-lg-8">
      <div class="card mb-4">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0"><i class="fas fa-user-circle"></i> Profile Information</h5>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <p class="text-muted">Name</p>
              <h6><?= htmlspecialchars($user['name']) ?></h6>
            </div>
            <div class="col-md-6">
              <p class="text-muted">Email</p>
              <h6><?= htmlspecialchars($user['email']) ?></h6>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p class="text-muted">Phone</p>
              <h6><?= htmlspecialchars($user['phone']) ?></h6>
            </div>
            <div class="col-md-6">
              <p class="text-muted">Address</p>
              <h6><?= htmlspecialchars($user['address']) ?></h6>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header bg-dark text-white">
          <h5 class="mb-0"><i class="fas fa-history"></i> Order History</h5>
        </div>
        <div class="card-body">
          <?php if (count($orders) === 0): ?>
            <p class="text-muted text-center py-3">No orders yet. <a href="../index.php">Start shopping</a></p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="table-light">
                  <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($orders as $o): ?>
                  <tr>
                    <td>#<?= $o['order_id'] ?></td>
                    <td><?= date('d M Y', strtotime($o['created_at'] ?? 'now')) ?></td>
                    <td>₹<?= number_format($o['total_amount']) ?></td>
                    <td>
                      <?php
                      $status_color = match($o['order_status']) {
                        'Delivered' => 'success',
                        'Shipped' => 'info',
                        'Pending' => 'warning',
                        default => 'secondary'
                      };
                      ?>
                      <span class="badge bg-<?= $status_color ?>">
                        <?= htmlspecialchars($o['order_status']) ?>
                      </span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <div class="card-body text-center">
          <i class="fas fa-shopping-bag fa-3x text-dark mb-3"></i>
          <h5>Quick Actions</h5>
          <a href="../index.php" class="btn btn-dark w-100 mb-2">
            <i class="fas fa-shopping-cart"></i> Continue Shopping
          </a>
          <a href="../cart_view.php" class="btn btn-outline-dark w-100 mb-2">
            <i class="fas fa-eye"></i> View Cart
          </a>
          <a href="logout.php" class="btn btn-outline-danger w-100">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
