<?php
require_once("dbconnect.php");

if (!isset($_GET['id'])) {
    die("Location ID not provided.");
}

$id = intval($_GET['id']);

// Prevent deleting locations assigned to employees
$check = mysqli_query($conn, "SELECT * FROM Employee WHERE location_id = $id");
if (mysqli_num_rows($check) > 0) {
    die("Cannot delete location. Employees are assigned to it.");
}

$sql = "DELETE FROM Location WHERE location_id = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: show_locations.php");
    exit();
} else {
    die("Delete failed: " . mysqli_error($conn));
}
