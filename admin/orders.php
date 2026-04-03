<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../config/db.php";
include "../config/auth.php";
include "includes/header.php";

$success = "";

// Update Order Status
if (isset($_POST['update'])) {
    $order_id = (int)$_POST['order_id'];
    $status = trim($_POST['status']);
    try {
        $stmt = $conn->prepare("UPDATE orders SET order_status=? WHERE order_id=?");
        $stmt->execute([$status, $order_id]);
        $success = "Order updated successfully!";
    } catch (Exception $e) {
        $success = "Error: " . $e->getMessage();
    }
}

$stmt = $conn->prepare("
    SELECT o.*, u.name, u.email, u.phone
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    ORDER BY o.order_id DESC
");
$stmt->execute();
$orders = $stmt->fetchAll();
?>

<div class="page-header mb-4">
  <h2 class="fw-bold"><i class="fas fa-shopping-bag"></i> Orders</h2>
</div>

<?php if (!empty($success)): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($orders as $o): ?>
          <tr>
            <td><strong>#<?= $o['order_id'] ?></strong></td>
            <td><?= htmlspecialchars($o['name']) ?></td>
            <td><?= htmlspecialchars($o['email']) ?></td>
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
              <form method="post" class="d-flex gap-2">
                <input type="hidden" name="order_id" value="<?= $o['order_id'] ?>">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                  <option value="Pending" <?= ($o['order_status'] === 'Pending') ? 'selected' : '' ?>>Pending</option>
                  <option value="Shipped" <?= ($o['order_status'] === 'Shipped') ? 'selected' : '' ?>>Shipped</option>
                  <option value="Delivered" <?= ($o['order_status'] === 'Delivered') ? 'selected' : '' ?>>Delivered</option>
                </select>
                <input type="hidden" name="update" value="1">
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>
