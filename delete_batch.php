<?php
require_once('DBconnect.php');

if(isset($_GET['id'])) {
    $batch_id = $_GET['id'];
    $sql = "DELETE FROM product_batches WHERE batch_id = '$batch_id'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_affected_rows($conn) > 0) {
        header("Location: show_batches.php");
    } else {
        echo "Error deleting batch: " . mysqli_error($conn);
    }
}
?>