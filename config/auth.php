<?php
// Check if session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: " . dirname(__FILE__) . "/../admin/login.php");
    exit;
}
