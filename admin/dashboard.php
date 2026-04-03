<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../config/db.php";

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include "includes/header.php";

try {
    $users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch()['count'] ?? 0;
    $products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch()['count'] ?? 0;
    $orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch()['count'] ?? 0;
    $revenue = $conn->query("SELECT SUM(total_amount) as total FROM orders")->fetch()['total'] ?? 0;
    $pending = $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status='Pending'")->fetch()['count'] ?? 0;

    // Recent Orders
    $recent = $conn->query("
        SELECT o.order_id, o.total_amount, o.order_status, o.created_at, u.name
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        ORDER BY o.order_id DESC LIMIT 5
    ");
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="admin-content">
  <div class="page-header mb-4">
    <h2 class="fw-bold"><i class="fas fa-dashboard"></i> Dashboard</h2>
    <p class="text-muted">Welcome back, Admin!</p>
  </div>

  <!-- STATS ROW -->
  <div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
      <div class="stat-card card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="stat-icon bg-primary">
              <i class="fas fa-users"></i>
            </div>
            <div class="ms-3">
              <h6 class="text-muted mb-0">Total Users</h6>
              <h3 class="fw-bold mb-0"><?= $users ?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
      <div class="stat-card card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="stat-icon bg-success">
              <i class="fas fa-box"></i>
            </div>
            <div class="ms-3">
              <h6 class="text-muted mb-0">Total Products</h6>
              <h3 class="fw-bold mb-0"><?= $products ?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
      <div class="stat-card card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="stat-icon bg-info">
              <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="ms-3">
              <h6 class="text-muted mb-0">Total Orders</h6>
              <h3 class="fw-bold mb-0"><?= $orders ?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
      <div class="stat-card card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="stat-icon bg-warning">
              <i class="fas fa-rupee-sign"></i>
            </div>
            <div class="ms-3">
              <h6 class="text-muted mb-0">Total Revenue</h6>
              <h3 class="fw-bold mb-0">₹<?= number_format($revenue) ?></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ALERTS & CHARTS ROW -->
  <div class="row mb-4">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-bottom">
          <h6 class="mb-0 fw-bold"><i class="fas fa-bell"></i> Pending Orders</h6>
        </div>
        <div class="card-body">
          <?php if ($pending > 0): ?>
            <div class="alert alert-warning mb-0">
              <i class="fas fa-exclamation-triangle"></i> <strong><?= $pending ?> order(s)</strong> pending attention
              <a href="orders.php" class="alert-link float-end">View Orders</a>
            </div>
          <?php else: ?>
            <div class="alert alert-success mb-0">
              <i class="fas fa-check-circle"></i> All orders are up to date
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-bottom">
          <h6 class="mb-0 fw-bold"><i class="fas fa-chart-pie"></i> Quick Stats</h6>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Orders Completed</span>
              <strong><?= $orders - $pending ?></strong>
            </div>
            <div class="progress">
              <div class="progress-bar bg-success" style="width: <?= ($orders > 0) ? (($orders - $pending) / $orders * 100) : 0 ?>%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- RECENT ORDERS -->
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-bottom">
      <h6 class="mb-0 fw-bold"><i class="fas fa-history"></i> Recent Orders</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while($o = $recent->fetch()): ?>
            <tr>
              <td><strong>#<?= $o['order_id'] ?></strong></td>
              <td><?= htmlspecialchars($o['name']) ?></td>
              <td>₹<?= number_format($o['total_amount']) ?></td>
              <td><?= date('d M Y', strtotime($o['created_at'] ?? 'now')) ?></td>
              <td>
                <?php
                $color = match($o['order_status']) {
                  'Delivered' => 'success',
                  'Shipped' => 'info',
                  'Pending' => 'warning',
                  default => 'secondary'
                };
                ?>
                <span class="badge bg-<?= $color ?>"><?= htmlspecialchars($o['order_status']) ?></span>
              </td>
              <td>
                <a href="orders.php" class="btn btn-sm btn-primary">
                  <i class="fas fa-edit"></i>
                </a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>
