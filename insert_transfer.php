<?php
require_once('DBconnect.php');

if(isset($_POST['batch_id']) && isset($_POST['quantity'])){
    $batch_id = $_POST['batch_id'];
    $dest_id = $_POST['destination'];
    $qty = $_POST['quantity'];

    // 1. Double check stock availability
    $check = mysqli_query($conn, "SELECT current_quantity FROM product_batches WHERE batch_id = '$batch_id'");
    $current = mysqli_fetch_assoc($check)['current_quantity'];

    if($current >= $qty) {
        // Start Transaction
        mysqli_begin_transaction($conn);
        try {
            // A. Insert the Transfer Record [cite: 31, 32]
            mysqli_query($conn, "INSERT INTO Batch_transfers (quantity, destination, batch_id) 
                                 VALUES ('$qty', '$dest_id', '$batch_id')");

            // B. Subtract from the original batch quantity [cite: 10]
            mysqli_query($conn, "UPDATE product_batches 
                                 SET current_quantity = current_quantity - $qty 
                                 WHERE batch_id = '$batch_id'");
            // C. 

// ================= NOTIFICATIONS =================

// Fetch destination branch name
$branchResult = mysqli_query(
    $conn,
    "SELECT name FROM location WHERE location_id = '$dest_id'"
);

$branchRow = mysqli_fetch_assoc($branchResult);
$branchName = $branchRow ? $branchRow['name'] : 'Unknown Branch';

// Notification for Hub Worker (stock going OUT)
$hubMessage = "Batch #$batch_id transferred OUT ($qty units) to $branchName Branch";

mysqli_query($conn, "
    INSERT INTO notifications (type, message, role, branch_id)
    VALUES ('ingredient', '$hubMessage', 'Hub Worker', 1)
");

// Notification for Branch Worker (stock coming IN)
$branchMessage = "Batch #$batch_id received ($qty units) at $branchName Branch";

mysqli_query($conn, "
    INSERT INTO notifications (type, message, role, branch_id)
    VALUES ('ingredient', '$branchMessage', 'Branch Worker', '$dest_id')
");

// =============== END NOTIFICATIONS ===============



            mysqli_commit($conn);
            header("Location: show_batches.php?msg=TransferComplete");
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "Transaction Failed: " . $e->getMessage();
        }
    } else {
        echo "Error: Not enough stock in Central Kitchen.";
    }
}
?>