<?php
session_start();
require_once("dbconnect.php");

// Get form data
$identifier = trim($_POST['identifier']);
$password   = $_POST['password'];

// Basic validation
if (empty($identifier) || empty($password)) {
    echo "<script>alert('Please fill in all fields'); window.location='home.php';</script>";
    exit();
}

// Find employee by email OR employee ID
$sql = "SELECT * FROM Employee 
        WHERE email = '$identifier' OR employee_id = '$identifier'
        LIMIT 1";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) !== 1) {
    echo "<script>alert('Invalid login credentials'); window.location='home.php';</script>";
    exit();
}

$employee = mysqli_fetch_assoc($result);

// Check if password has been set
if (is_null($employee['password_hash'])) {
    echo "<script>
            alert('Please sign up first and set your password.');
            window.location='signup.php';
          </script>";
    exit();
}

// Verify password
if (!password_verify($password, $employee['password_hash'])) {
    echo "<script>alert('Invalid login credentials'); window.location='home.php';</script>";
    exit();
}

// LOGIN SUCCESS → create session
$_SESSION['logged_in']   = true;
$_SESSION['employee_id'] = $employee['employee_id'];
$_SESSION['first_name']  = $employee['first_name'];
$_SESSION['role']        = $employee['role'];
$_SESSION['location_id'] = $employee['location_id'];

// Redirect to dashboard
header("Location: index.php");
exit();
