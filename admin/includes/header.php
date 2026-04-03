<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Panel - Soul Store</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="admin-container">
  <!-- SIDEBAR -->
  <aside class="admin-sidebar">
    <div class="sidebar-header">
      <h5 class="fw-bold mb-0">
        <i class="fas fa-heart text-danger"></i> Soul Store
      </h5>
      <p class="text-muted small mb-0">Admin Panel</p>
    </div>

    <nav class="sidebar-menu mt-4">
      <a href="dashboard.php" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) === 'dashboard.php') ? 'active' : '' ?>">
        <i class="fas fa-dashboard"></i> Dashboard
      </a>
      <a href="orders.php" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) === 'orders.php') ? 'active' : '' ?>">
        <i class="fas fa-shopping-bag"></i> Orders
      </a>
      <a href="products.php" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) === 'products.php') ? 'active' : '' ?>">
        <i class="fas fa-box"></i> Products
      </a>
      <a href="categories.php" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) === 'categories.php') ? 'active' : '' ?>">
        <i class="fas fa-list"></i> Categories
      </a>
      <a href="users.php" class="sidebar-link <?= (basename($_SERVER['PHP_SELF']) === 'users.php') ? 'active' : '' ?>">
        <i class="fas fa-users"></i> Users
      </a>
    </nav>

    <div class="sidebar-footer">
      <a href="logout.php" class="sidebar-link text-danger">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="admin-main">
    <!-- TOP BAR -->
    <div class="admin-topbar">
      <div class="topbar-left">
        <button class="sidebar-toggle btn btn-light" onclick="toggleSidebar()">
          <i class="fas fa-bars"></i>
        </button>
      </div>
      <div class="topbar-right">
        <span class="me-3">
          Welcome, <strong><?= htmlspecialchars($_SESSION['admin_email'] ?? 'Admin') ?></strong>
        </span>
        <img src="https://via.placeholder.com/40" class="rounded-circle" alt="Admin">
      </div>
    </div>

    <!-- PAGE CONTENT -->
    <div class="admin-body">
