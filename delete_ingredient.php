<?php
require_once('DBconnect.php'); 

// 1. Check if the 'id' was passed in the URL
if(isset($_GET['id'])) {
    
    // 2. Retrieve the ID from the URL
    $ingredient_id = $_GET['id'];
    
    // 3. Construct the simple SQL query
    $sql = "DELETE FROM Ingredients WHERE ingredient_id = '$ingredient_id'";
    
    // 4. Run the query using mysqli_query
    $result = mysqli_query($conn, $sql);

    // 5. Check if the deletion was successful
    if(mysqli_affected_rows($conn) > 0) {
        // Success: Go back to the ingredients list
        header("Location: show_ingredients.php");
    } else {
        // Failure: Show the error message
        echo "Error deleting ingredient: " . mysqli_error($conn);
    }
}
?>