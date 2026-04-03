<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../config/db.php";
include "../config/auth.php";
include "includes/header.php";

$error = "";
$success = "";

if (isset($_POST['add'])) {
    $name = trim($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $cat = (int)($_POST['category'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    if (empty($name) || $price <= 0 || $stock < 0 || $cat <= 0) {
        $error = "Please fill all fields correctly";
    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = "Please select a valid image file";
    } else {
        $file = $_FILES['image'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];

        // Validate file
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($file_ext, $allowed_extensions)) {
            $error = "Invalid file type. Allowed: JPG, PNG, GIF, WEBP";
        } elseif ($file_size > $max_size) {
            $error = "File size exceeds 5MB limit";
        } else {
            // Create uploads directory if it doesn't exist
            $upload_dir = realpath(dirname(__FILE__) . "/..") . "/uploads/";
            
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    $error = "Failed to create uploads directory. Check server permissions.";
                }
            }

            if (empty($error)) {
                // Generate unique filename
                $new_file_name = time() . "_" . bin2hex(random_bytes(8)) . "." . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    try {
                        $stmt = $conn->prepare(
                            "INSERT INTO products(name, price, image, stock, category_id, description)
                             VALUES(?, ?, ?, ?, ?, ?)"
                        );
                        $stmt->execute([$name, $price, $new_file_name, $stock, $cat, $description]);
                        $success = "Product added successfully!";
                    } catch (PDOException $e) {
                        unlink($upload_path); // Delete uploaded file if DB insert fails
                        $error = "Database error: " . $e->getMessage();
                    }
                } else {
                    $error = "Failed to upload image. Check folder permissions at: " . htmlspecialchars($upload_dir);
                }
            }
        }
    }
}

// Delete Product
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    try {
        // Get image path to delete
        $product = $conn->prepare("SELECT image FROM products WHERE product_id=?");
        $product->execute([$id]);
        $p = $product->fetch();
        
        if ($p && !empty($p['image'])) {
            $image_path = realpath(dirname(__FILE__) . "/..") . "/uploads/" . $p['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id=?");
        $stmt->execute([$id]);
        header("Location: products.php");
        exit;
    } catch (Exception $e) {
        $error = "Failed to delete product: " . $e->getMessage();
    }
}

$stmt = $conn->prepare("
    SELECT p.*, c.category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    ORDER BY p.product_id DESC
");
$stmt->execute();
$products = $stmt->fetchAll();

// Get categories for the dropdown
$categories_result = $conn->query("SELECT * FROM categories ORDER BY category_name");
$categories_list = [];
while ($cat = $categories_result->fetch()) {
    $categories_list[] = $cat;
}
?>

<div class="page-header mb-4">
  <h2 class="fw-bold"><i class="fas fa-box"></i> Products</h2>
  <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addProduct">
    <i class="fas fa-plus"></i> Add Product
  </button>
</div>

<?php if (!empty($success)): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($products as $p): ?>
          <tr>
            <td>
              <?php 
              $upload_dir = realpath(dirname(__FILE__) . "/..") . "/uploads/";
              $image_path = $upload_dir . $p['image'];
              if (!empty($p['image']) && file_exists($image_path)): 
              ?>
                <img src="../uploads/<?= htmlspecialchars($p['image']) ?>" class="img-thumbnail" style="max-width: 50px; height: 50px; object-fit: cover;">
              <?php else: ?>
                <img src="https://via.placeholder.com/50" class="img-thumbnail" style="max-width: 50px; height: 50px;">
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><?= htmlspecialchars($p['category_name'] ?? 'N/A') ?></td>
            <td><strong>₹<?= number_format($p['price']) ?></strong></td>
            <td>
              <span class="badge <?= ($p['stock'] > 0) ? 'bg-success' : 'bg-danger' ?>">
                <?= $p['stock'] ?>
              </span>
            </td>
            <td>
              <a href="?delete=<?= $p['product_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">
                <i class="fas fa-trash"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ADD PRODUCT MODAL -->
<div class="modal fade" id="addProduct" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus"></i> Add New Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input class="form-control" type="text" name="name" placeholder="Enter product name" required>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Price (₹)</label>
                <input class="form-control" type="number" name="price" step="0.01" placeholder="0.00" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Stock</label>
                <input class="form-control" type="number" name="stock" placeholder="0" required>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Category</label>
            <select class="form-control" name="category" required>
              <option value="">-- Select Category --</option>
              <?php 
              if (count($categories_list) > 0) {
                  foreach ($categories_list as $cat) {
                      echo '<option value="' . $cat['category_id'] . '">' . htmlspecialchars($cat['category_name']) . '</option>';
                  }
              } else {
                  echo '<option value="">No categories available</option>';
              }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="3" placeholder="Product description (optional)"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input class="form-control" type="file" name="image" accept="image/*" required>
            <small class="text-muted">Allowed formats: JPG, PNG, GIF, WEBP | Max size: 5MB</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="add" class="btn btn-dark">Add Product</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>
