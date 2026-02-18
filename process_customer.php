<?php
require_once("auth.php");
// This ensures ONLY these two roles can even load this page
require_role(['Admin','Branch Manager', 'Branch Worker']); 
require 'DBconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // 1. Check if Phone already exists
    $checkSql = "SELECT customer_id FROM Customers WHERE phone_number = '$phone'";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        // Phone found -> Stop and Alert
        echo "<script>
                alert('Error: This phone number is already registered!'); 
                window.location.href='customers.php';
              </script>";
    } else {
        // 2. Insert New
        $sql = "INSERT INTO Customers (name, phone_number, email, points) VALUES ('$name', '$phone', '$email', 0)";
        
        if (mysqli_query($conn, $sql)) {
            $new_id = mysqli_insert_id($conn);
            // Redirect to the Order page immediately with the new ID
            header("Location: create_order.php?customer_id=$new_id"); 
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>