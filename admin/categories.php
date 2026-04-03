<?php
include "../config/db.php";
include "../config/auth.php";
include "includes/header.php";

$error = "";
$success = "";

if (isset($_POST['add'])) {
    $name = trim($_POST['category']);
    if (empty($name)) {
        $error = "Category name cannot be empty";
    } else {
        $stmt = $conn->prepare("INSERT INTO categories(category_name) VALUES (?)");
        $stmt->execute([$name]);
        $success = "Category added successfully!";
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE category_id=?");
    $stmt->execute([$id]);
    header("Location: categories.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM categories ORDER BY category_id DESC");
$stmt->execute();
$categories = $stmt->fetchAll();
?>

<div class="page-header mb-4">
  <h2 class="fw-bold"><i class="fas fa-list"></i> Categories</h2>
  <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addCategory">
    <i class="fas fa-plus"></i> Add Category
  </button>
</div>

<?php if ($success): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> <?= $success ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle"></i> <?= $error ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="row">
  <?php foreach($categories as $c): ?>
  <div class="col-md-4 mb-3">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="card-title"><?= htmlspecialchars($c['category_name']) ?></h6>
        <a href="?delete=<?= $c['category_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">
          <i class="fas fa-trash"></i> Delete
        </a>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- ADD CATEGORY MODAL -->
<div class="modal fade" id="addCategory" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus"></i> Add New Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="post">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Category Name</label>
            <input class="form-control" type="text" name="category" placeholder="e.g., Men, Women, Accessories" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="add" class="btn btn-dark">Add Category</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>
