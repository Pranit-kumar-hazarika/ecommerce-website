<?php
include "../config/db.php";
include "../config/auth.php";
include "includes/header.php";

$users = $conn->query("
    SELECT u.*, COUNT(o.order_id) as total_orders, SUM(o.total_amount) as total_spent
    FROM users u
    LEFT JOIN orders o ON u.user_id = o.user_id
    GROUP BY u.user_id
    ORDER BY u.user_id DESC
");
?>

<div class="page-header mb-4">
  <h2 class="fw-bold"><i class="fas fa-users"></i> Users</h2>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Orders</th>
            <th>Spent</th>
            <th>Joined</th>
          </tr>
        </thead>
        <tbody>
          <?php while($u = $users->fetch()): ?>
          <tr>
            <td><strong>#<?= $u['user_id'] ?></strong></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['phone']) ?></td>
            <td>
              <span class="badge bg-primary"><?= ($u['total_orders'] ?? 0) ?></span>
            </td>
            <td>₹<?= number_format($u['total_spent'] ?? 0) ?></td>
            <td>
              <?php 
              $created = isset($u['created_at']) ? strtotime($u['created_at']) : time();
              echo date('d M Y', $created);
              ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>
