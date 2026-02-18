<?php
require_once('DBconnect.php'); 

// 1. Turn off fatal error reporting to handle duplicate entries simply
mysqli_report(MYSQLI_REPORT_OFF); 

// 2. Check if the required data was sent from the form
if(isset($_POST['product_id']) && isset($_POST['ingredient_id']) && isset($_POST['quantity'])){
    
    // 3. Collect the data into variables
    $product_id = $_POST['product_id'];
    $ingredient_id = $_POST['ingredient_id'];
    $quantity = $_POST['quantity'];
    
    // 4. Construct the simple SQL query
    $sql = "INSERT INTO Used_in (product_id, ingredient_id, quantity) 
            VALUES ('$product_id', '$ingredient_id', '$quantity')";
    
    // 5. Run the query with '@' to suppress system errors
    $result = @mysqli_query($conn, $sql);
    
    // 6. Check if it worked and redirect
    if($result){
        // Success: Redirect back to the recipe manager for this specific product
        header("Location: manage_recipe.php?product_id=$product_id");
    }
    else{
        // Failure: Likely a duplicate entry error (ingredient already in recipe)
        echo "<h1 style='color:red;'>Insertion Failed</h1>";
        echo "<p>This ingredient is already part of the recipe for this product.</p>";
        echo "<a href='manage_recipe.php?product_id=$product_id'>Go back to Manage Recipe</a>";
    }
}
?>