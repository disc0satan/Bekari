<?php
require_once("auth.php");
require_role(['Admin']);
require_once("dbconnect.php");

if (!isset($_GET['id'])) {
    die("Employee ID not provided.");
}

$id = intval($_GET['id']);

$sql = "DELETE FROM Employee WHERE employee_id = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: show_employee.php");
    exit();
} else {
    die("Delete failed: " . mysqli_error($conn));
}
