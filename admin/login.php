<?php
session_start();
include "../config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    if (empty($email) || empty($pass)) {
        $error = "Please fill in all fields";
    } else {
        try {
            $stmt = $conn->prepare("SELECT admin_id, email, password FROM admins WHERE email = ?");
            $stmt->execute([$email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin) {
                // Debug: Check if password matches
                if (password_verify($pass, $admin['password'])) {
                    $_SESSION['admin'] = $admin['admin_id'];
                    $_SESSION['admin_email'] = $admin['email'];
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Invalid password. Please try again.";
                }
            } else {
                $error = "No admin found with this email.";
            }
        } catch (PDOException $e) {
            $error = "Database error: Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login - Soul Store</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-login-page">

<div class="container">
  <div class="row justify-content-center min-vh-100 align-items-center">
    <div class="col-md-5">
      <div class="admin-login-box card shadow-lg border-0">
        <div class="card-body p-5">
          <div class="text-center mb-4">
            <i class="fas fa-lock fa-3x text-dark mb-3"></i>
            <h3 class="fw-bold">Admin Panel</h3>
            <p class="text-muted">Soul Store Management</p>
          </div>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <form method="post" action="">
            <div class="mb-4">
              <label class="form-label fw-bold">Email Address</label>
              <input class="form-control form-control-lg" type="email" name="email" 
                     placeholder="admin@soulstore.com" required autofocus>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold">Password</label>
              <input class="form-control form-control-lg" type="password" name="password" 
                     placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-dark btn-lg w-100 mb-3">
              <i class="fas fa-sign-in-alt"></i> Login
            </button>
          </form>

          <hr>
          <div class="text-center">
            <p class="text-muted small mb-0">© <?= date("Y") ?> Soul Store Admin Panel</p>
            <p class="text-muted small">Restricted Access Only</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
