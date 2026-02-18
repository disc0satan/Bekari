<?php
require 'DBconnect.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Optional Safety Check: You might want to delete their orders first?
    // For now, we will just delete the customer.
    // Note: If you have Foreign Keys set up in your database (ON DELETE RESTRICT),
    // this might fail if the customer has existing orders. 
    
    $sql = "DELETE FROM Customers WHERE customer_id = '$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: customer_list.php?msg=deleted");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
        echo "<br><strong>Hint:</strong> You cannot delete a customer who still has active Orders in the database.";
    }
} else {
    header("Location: customer_list.php");
}
?>