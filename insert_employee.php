<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("dbconnect.php");

// 1. Sanitize inputs to prevent SQL injection
$first_name  = mysqli_real_escape_string($conn, $_POST['first_name']);
$last_name   = mysqli_real_escape_string($conn, $_POST['last_name']);
$role        = mysqli_real_escape_string($conn, $_POST['role']);
$email       = mysqli_real_escape_string($conn, $_POST['email']);
$location_id = isset($_POST['location_id']) ? mysqli_real_escape_string($conn, $_POST['location_id']) : NULL;

// 2. Insert the Employee
$sql = "INSERT INTO Employee (first_name, last_name, email, role, location_id)
        VALUES ('$first_name', '$last_name', '$email', '$role', '$location_id')";

if (mysqli_query($conn, $sql)) {

    // --- NOTIFICATION LOGIC START ---
    $full_name = $first_name . ' ' . $last_name;
    
    // UPDATED: Using 'employee_id' instead of 'id'
    $notif_sql = "INSERT INTO Notifications (user_id, location_id, message, created_at)
                  
                  -- 1. Notify Admins (Selects 'employee_id' from Employee table)
                  SELECT employee_id, NULL, 'New Worker Added: $full_name', NOW()
                  FROM Employee 
                  WHERE role = 'admin'
                  
                  UNION ALL
                  
                  -- 2. Notify the Branch
                  SELECT NULL, '$location_id', 'New Worker ($full_name) added to your branch', NOW()";
                  
    // We suppress errors (@) here so a notification failure doesn't stop the redirect
    @mysqli_query($conn, $notif_sql);
    // --- NOTIFICATION LOGIC END ---

    header("Location: show_employee.php");
    exit();

} else {
    echo "Insert failed: " . mysqli_error($conn);
}
?>


