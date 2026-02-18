<?php
require_once('dbconnect.php'); 

// 1. Check if the 'id' was passed in the URL
if(isset($_GET['id'])) {
    
    // 2. Retrieve the product ID from the URL
    $product_id = $_GET['id'];
    
    // 3. Construct the simple SQL query
    $sql = "DELETE FROM Products WHERE product_id = '$product_id'";
    
    // 4. Run the query using mysqli_query
    $result = mysqli_query($conn, $sql);

    // 5. Check if the deletion worked and redirect
    if(mysqli_affected_rows($conn) > 0) {
        // Success: Redirect back to the products list
        header("Location: show_products.php");
    } else {
        // Failure: Show the error message
        echo "Error deleting product: " . mysqli_error($conn);
    }
}
?>