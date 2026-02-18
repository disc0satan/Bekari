<?php
require_once('DBconnect.php');

if(isset($_GET['id'])) {
    $transfer_id = mysqli_real_escape_string($conn, $_GET['id']);

    // 1. Get transfer details before deleting
    $query = "SELECT batch_id, quantity FROM Batch_transfers WHERE batch_transfer_id = '$transfer_id'";
    $res = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($res);

    if($data) {
        $batch_id = $data['batch_id'];
        $qty = $data['quantity'];

        mysqli_begin_transaction($conn);
        try {
            // 2. Return the stock to the central kitchen
            mysqli_query($conn, "UPDATE product_batches SET current_quantity = current_quantity + $qty WHERE batch_id = '$batch_id'");

            // 3. Delete the transfer record
            mysqli_query($conn, "DELETE FROM Batch_transfers WHERE batch_transfer_id = '$transfer_id'");

            mysqli_commit($conn);
            header("Location: show_transfer.php");
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "Error: " . $e->getMessage();
        }
    }
}
?>