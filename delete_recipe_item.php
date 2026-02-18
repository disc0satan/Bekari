<?php
require_once('DBconnect.php'); 

// 1. Check if both product_id and ingredient_id were passed in the URL
if(isset($_GET['product_id']) && isset($_GET['ingredient_id'])) {
    
    // 2. Retrieve the IDs from the URL
    $product_id = $_GET['product_id'];
    $ingredient_id = $_GET['ingredient_id'];
    
    // 3. Construct the simple SQL query
    // We use AND because we need to delete the row where BOTH IDs match
    $sql = "DELETE FROM Used_in WHERE product_id = '$product_id' AND ingredient_id = '$ingredient_id'";
    
    // 4. Run the query
    $result = mysqli_query($conn, $sql);

    // 5. Check if the deletion worked and redirect
    if(mysqli_affected_rows($conn) > 0) {
        // Success: Redirect back to the recipe manager for that specific product
        header("Location: manage_recipe.php?product_id=$product_id");
    } else {
        // Failure: Show the error message
        echo "Error deleting recipe item: " . mysqli_error($conn);
    }
}
?>