<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=ecommerce_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database Connection Failed");
}
