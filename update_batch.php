<?php
require_once('DBconnect.php');

if(isset($_POST['batch_id'])){
    $batch_id = $_POST['batch_id'];
    $current_qty = $_POST['new_current_quantity'];

    // Only update quantity; status is handled by the show_batches.php automation
    $sql = "UPDATE product_batches 
            SET current_quantity = '$current_qty' 
            WHERE batch_id = '$batch_id'";

    $result = mysqli_query($conn, $sql);

    if($result){
        header("Location: show_batches.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>