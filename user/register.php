<?php
session_start();
include "../config/db.php";

$success = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];
    $cpass = $_POST['confirm_password'];
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (empty($name) || empty($email) || empty($pass) || empty($phone) || empty($address)) {
        $error = "All fields are required";
    } elseif ($pass !== $cpass) {
        $error = "Passwords do not match";
    } elseif (strlen($pass) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare(
                "INSERT INTO users(name,email,password,phone,address) VALUES(?,?,?,?,?)"
            );
            $stmt->execute([$name, $email, $hash, $phone, $address]);
            $success = true;
        }
    }
}
?>
<?php include "../includes/header.php"; ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="auth-box card shadow-lg">
        <div class="card-body">
          <h4 class="card-title text-center mb-4">
            <i class="fas fa-user-plus"></i> Create Account
          </h4>

          <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="fas fa-check-circle"></i> Account created successfully! <a href="login.php">Login now</a>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <form method="post">
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input class="form-control" type="text" name="name" placeholder="John Doe" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input class="form-control" type="email" name="email" placeholder="your@email.com" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Phone Number</label>
              <input class="form-control" type="tel" name="phone" placeholder="9876543210" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Address</label>
              <textarea class="form-control" name="address" rows="2" placeholder="Your address" required></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Password</label>
              <input class="form-control" type="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Confirm Password</label>
              <input class="form-control" type="password" name="confirm_password" placeholder="••••••••" required>
            </div>

            <button class="btn btn-dark w-100 mb-3 py-2">
              <i class="fas fa-user-plus"></i> Register
            </button>
          </form>

          <hr>
          <p class="text-center text-muted">Already have an account?</p>
          <a href="login.php" class="btn btn-outline-dark w-100">
            <i class="fas fa-sign-in-alt"></i> Login Here
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
