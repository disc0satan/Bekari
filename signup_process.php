<?php
session_start();
require_once("dbconnect.php");

// Get form data
$identifier       = trim($_POST['identifier']);
$password         = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Basic validation
if (empty($identifier) || empty($password) || empty($confirm_password)) {
    echo "<script>alert('All fields are required'); window.location='signup.php';</script>";
    exit();
}

if ($password !== $confirm_password) {
    echo "<script>alert('Passwords do not match'); window.location='signup.php';</script>";
    exit();
}

// Find employee
$sql = "SELECT * FROM Employee WHERE email = '$identifier' OR employee_id = '$identifier' LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) !== 1) {
    echo "<script>alert('Employee not found'); window.location='signup.php';</script>";
    exit();
}

$employee = mysqli_fetch_assoc($result);

// Check if password already set
if (!is_null($employee['password_hash'])) {
    echo "<script>alert('Password already set. Please login.'); window.location='home.php';</script>";
    exit();
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Update employee table
$update_sql = "UPDATE Employee SET password_hash='$password_hash' WHERE employee_id=".$employee['employee_id'];
if (mysqli_query($conn, $update_sql)) {
    echo "<script>alert('Password set successfully! Please login.'); window.location='home.php';</script>";
} else {
    echo "<script>alert('Error updating password. Try again.'); window.location='signup.php';</script>";
}
