<?php
require_once('DBconnect.php'); 

// 1. Turn off fatal error reporting to handle duplicate name errors simply
mysqli_report(MYSQLI_REPORT_OFF);

if(isset($_POST['product_id'])){
    
    // 2. Collect the updated data into simple variables
    $product_id = $_POST['product_id'];
    $name = $_POST['new_product_name'];
    $base_price = $_POST['new_base_price'];
    $shelf_life = $_POST['new_shelf_life'];
    $markdown_hours = $_POST['new_markdown_hours'];
    $discount_pct = $_POST['new_discount_pct'];

    // 3. Construct the simple SQL UPDATE query
    $sql = "UPDATE Products SET 
                name = '$name', 
                base_price = '$base_price', 
                shelf_life = '$shelf_life', 
                markdown_hours = '$markdown_hours', 
                discount_pct = '$discount_pct' 
            WHERE 
                product_id = '$product_id'";

    // 4. Run the query using the demo style
    $result = @mysqli_query($conn, $sql);
    
    // 5. Check if it worked and redirect
    if($result){
        // Success: Go back to the products list
        header("Location: show_products.php");
    } else {
        // Failure: Likely a duplicate name error
        echo "<h1 style='color:red; text-align:center;'>Update Failed</h1>";
        echo "<p style='text-align:center;'>A product named '<strong>$name</strong>' already exists.</p>";
        echo "<p style='text-align:center;'><a href='modify_product.php?id=$product_id'>Go back and try again</a></p>";
    }
}
?>