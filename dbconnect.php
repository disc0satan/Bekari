<?php
// 1. Force PHP to Bangladesh Time
date_default_timezone_set('Asia/Dhaka');

// --- MySQLi Connection ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bekari";

// Create mysqli connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("MySQLi Connection Failed!: ". $conn->connect_error);
} else {
    // Force MySQLi to use Bangladesh timezone
    mysqli_query($conn, "SET time_zone = '+06:00'");
}

// --- PDO Connection ---
$dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Force PDO to use Bangladesh timezone
    $pdo->exec("SET time_zone = '+06:00'");
} catch (PDOException $e) {
    die("PDO Connection Failed: " . $e->getMessage());
}
?>
