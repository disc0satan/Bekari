<?php
require_once('DBconnect.php');

if(isset($_POST['transfer_id'])){
    $t_id = $_POST['transfer_id'];
    $b_id = $_POST['batch_id'];
    $old_qty = $_POST['old_qty'];
    $new_qty = $_POST['new_qty'];
    $dest = $_POST['destination'];

    // 1. Calculate difference
    // If new_qty is larger, we subtract more from central kitchen.
    // If new_qty is smaller, we return some to central kitchen.
    $difference = $new_qty - $old_qty;

    mysqli_begin_transaction($conn);
    try {
        // 2. Adjust central kitchen stock
        mysqli_query($conn, "UPDATE product_batches SET current_quantity = current_quantity - $difference WHERE batch_id = '$b_id'");

        // 3. Update transfer log
        mysqli_query($conn, "UPDATE Batch_transfers SET quantity = '$new_qty', destination = '$dest' WHERE batch_transfer_id = '$t_id'");

        mysqli_commit($conn);
        header("Location: show_transfer.php");
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
}
?>