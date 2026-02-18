<?php
require_once('DBconnect.php');

if(isset($_POST['product_id']) && isset($_POST['quantity'])){
    $p_id = $_POST['product_id'];
    $qty = $_POST['quantity'];
    
    // Check for custom timestamp or use default
    if(!empty($_POST['made_at'])){
        $timestamp = $_POST['made_at'];
        $sql = "INSERT INTO product_batches (product_id, initial_quantity, current_quantity, made_timestamp, status) 
                VALUES ('$p_id', '$qty', '$qty', '$timestamp', 0)";
    } else {
        $sql = "INSERT INTO product_batches (product_id, initial_quantity, current_quantity, status) 
                VALUES ('$p_id', '$qty', '$qty', 0)";
    }

    $result = mysqli_query($conn, $sql);

    if(mysqli_affected_rows($conn) > 0){
        header("Location: show_batches.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>