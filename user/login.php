<?php
session_start();
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user'] = $user['user_id'];
        header("Location: profile.php");
        exit;
    } else {
        $error = "Invalid Email or Password";
    }
}
?>
<?php include "../includes/header.php"; ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="auth-box card shadow-lg">
        <div class="card-body">
          <h4 class="card-title text-center mb-4">
            <i class="fas fa-sign-in-alt"></i> User Login
          </h4>

          <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <form method="post">
            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input class="form-control" type="email" name="email" placeholder="your@email.com" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Password</label>
              <input class="form-control" type="password" name="password" placeholder="••••••••" required>
            </div>

            <button class="btn btn-dark w-100 mb-3 py-2">
              <i class="fas fa-lock"></i> Login
            </button>
          </form>

          <hr>
          <p class="text-center text-muted">Don't have an account?</p>
          <a href="register.php" class="btn btn-outline-dark w-100">
            <i class="fas fa-user-plus"></i> Create Account
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
