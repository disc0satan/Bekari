<?php
require_once('DBconnect.php'); 
// This line tells PHP NOT to throw a "Fatal Error" on duplicate entries
mysqli_report(MYSQLI_REPORT_OFF);

if(isset($_POST['new_product_name']) && isset($_POST['new_base_price']) && isset($_POST['new_shelf_life'])){
    
    $name = $_POST['new_product_name'];
    $base_price = $_POST['new_base_price'];
    $shelf_life = $_POST['new_shelf_life'];
    $markdown_hours = $_POST['new_markdown_hours'];
    $discount_pct = $_POST['new_discount_pct'];
    
    $sql = "INSERT INTO Products (name, base_price, shelf_life, markdown_hours, discount_pct) 
            VALUES ('$name', '$base_price', '$shelf_life', '$markdown_hours', '$discount_pct')";
    
    // The '@' symbol suppresses the "Fatal Error" message so we can handle it ourselves
    $result = @mysqli_query($conn, $sql);
    
    if($result){
        // Success: Product was unique and inserted
        header("Location: show_products.php");
    }
    else{
        // Failure: Likely a duplicate name error
        echo "<h1 style='color:red;'>Insertion Failed</h1>";
        echo "<p>The product '<strong>$name</strong>' already exists in the inventory.</p>";
        echo "<a href='add_product.php'>Try again with a different name</a>";
    }
}
?>