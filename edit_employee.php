<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("auth.php");
require_role(['Admin']);
require_once("dbconnect.php");

// Get employee ID from URL
if (!isset($_GET['id'])) {
    die("Employee ID not provided.");
}

$id = intval($_GET['id']);

// Fetch employee data
$sql = "SELECT * FROM Employee WHERE employee_id = $id";
$result = mysqli_query($conn, $sql);
if (!$result || mysqli_num_rows($result) == 0) {
    die("Employee not found.");
}
$employee = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name  = mysqli_real_escape_string($conn, $_POST['last_name']);
    $role       = mysqli_real_escape_string($conn, $_POST['role']);
    $location_id = !empty($_POST['location_id']) ? intval($_POST['location_id']) : NULL;

    $update_sql = "UPDATE Employee SET 
        first_name = '$first_name',
        last_name = '$last_name',
        role = '$role',
        location_id = ".($location_id !== NULL ? $location_id : "NULL")."
        WHERE employee_id = $id";

    if (mysqli_query($conn, $update_sql)) {
        header("Location: show_employee.php");
        exit();
    } else {
        die("Update failed: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <nav>
        <div class="nav_logo"><h1><a href="index.php">Bakery</a></h1></div>
        <ul class="nav_link">
            <li><a href="show_employee.php" class="active">Employees</a></li>
            <li><a href="show_products.php">Products</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="form_container">
        <h2>Edit Employee</h2>

        <form method="POST">
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($employee['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($employee['last_name']); ?>" required>
            </div>
            <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
</div>

            <div class="form-group">
                <label>Role</label>
<select name="role" required>
    <option value="">-- Select Role --</option>
    <option value="Admin">Admin</option>
    <option value="Branch Manager">Branch Manager</option>
    <option value="Branch Worker">Branch Worker</option>
    <option value="Hub Manager">Hub Manager</option>
    <option value="Hub Worker">Hub Worker</option>
</select>
</select>

            </div>
            <div class="form-group">
                <label>Location ID:</label>
                <input type="number" name="location_id" value="<?= htmlspecialchars($employee['location_id']); ?>">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Update Employee</button>
                <a href="show_employee.php" class="btn cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</main>

</body>
</html>