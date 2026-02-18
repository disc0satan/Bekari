<?php
require_once('DBconnect.php'); 

// 1. Turn off fatal error reporting so the duplicate check works simply
mysqli_report(MYSQLI_REPORT_OFF); 

// 2. Check if the required data was sent from the form
if(isset($_POST['new_name']) && isset($_POST['new_current_stock']) && isset($_POST['new_reorder_point']) && isset($_POST['new_measure_unit'])){
    
    // 3. Collect the data into variables
    $name = $_POST['new_name'];
    $current_stock = $_POST['new_current_stock'];
    $reorder_point = $_POST['new_reorder_point'];
    $measure_unit = $_POST['new_measure_unit'];
    
    // 4. Construct the simple SQL query
    $sql = "INSERT INTO Ingredients (name, current_stock, reorder_point, measure_unit) 
            VALUES ('$name', '$current_stock', '$reorder_point', '$measure_unit')";
    
    // 5. Run the query with '@' to suppress system errors
    $result = @mysqli_query($conn, $sql);
    
    // 6. Check if it worked and redirect
    if($result){
        // Success: Redirect to ingredients list
        header("Location: show_ingredients.php");
    }
    else{
        // Failure: Likely a duplicate name error
        echo "<h1 style='color:red;'>Insertion Failed</h1>";
        echo "<p>The ingredient '<strong>$name</strong>' already exists.</p>";
        echo "<a href='add_ingredient.php'>Try again with a different name</a>";
    }
}
?>