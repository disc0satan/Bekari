<?php
require_once('DBconnect.php'); 

// 1. Turn off fatal error reporting so we can handle duplicate name errors simply
mysqli_report(MYSQLI_REPORT_OFF);

if(isset($_POST['ingredient_id'])){
    
    // 2. Collect the updated data into simple variables
    $ingredient_id = $_POST['ingredient_id'];
    $name = $_POST['new_name'];
    $measure_unit = $_POST['new_measure_unit'];
    $current_stock = $_POST['new_current_stock'];
    $reorder_point = $_POST['new_reorder_point'];

    // 3. Construct the simple SQL UPDATE query
    $sql = "UPDATE Ingredients SET 
                name = '$name', 
                current_stock = '$current_stock', 
                reorder_point = '$reorder_point', 
                measure_unit = '$measure_unit' 
            WHERE 
                ingredient_id = '$ingredient_id'";

    // 4. Execute the query using the demo style
    $result = @mysqli_query($conn, $sql);

    
    
    // 5. Check if it worked and redirect
    if($result){
        if($current_stock <= $reorder_point){
        $message = "Ingredient $name has reached reorder point";
        $notif_sql = "INSERT INTO notifications (type, message, role,branch_id) 
                      VALUES ('ingredient', '$message', 'Hub Worker',1)";
        mysqli_query($conn, $notif_sql);
    }
        
        // Success: Go back to the ingredients list
        header("Location: show_ingredients.php");
    } else {
        // Failure: Likely a duplicate name error
        echo "<h1 style='color:red ; text-align:center;'>Update Failed</h1>";
        echo "<p style = 'text-align:center;'>The name '<strong>$name</strong>' is already used by another ingredient.</p>";
        echo "<p style='text-align:center;'><a href='modify_ingredient.php?id=$ingredient_id'>Go back and try again</a>";
    }
}
?>