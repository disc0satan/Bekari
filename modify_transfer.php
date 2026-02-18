<?php
require_once("auth.php");
require 'DBconnect.php';
include('header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Transfer - Bekari</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main>
        <section class="form_container">
            <div class="list_box" style="border: 1px solid #ffc0cb; padding: 40px; border-radius: 15px; background-color: rgba(255, 192, 203, 0.05); max-width: 800px; margin: 40px auto;">
                <h2 style="text-align: center; color: #333; margin-bottom: 30px; font-family: 'Dancing Script', cursive;">Edit Transfer Record</h2>
                
                <?php
                if(isset($_GET['id'])){
                    $id = $_GET['id'];
                    $sql = "SELECT bt.*, p.name FROM Batch_transfers bt 
                            JOIN product_batches b ON bt.batch_id = b.batch_id 
                            JOIN Products p ON b.product_id = p.product_id 
                            WHERE bt.batch_transfer_id = '$id'";
                    $res = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($res);
                ?>
                <form action="update_transfer.php" method="post" class="data_form">
                    <input type="hidden" name="transfer_id" value="<?= $row['batch_transfer_id']; ?>">
                    <input type="hidden" name="batch_id" value="<?= $row['batch_id']; ?>">
                    <input type="hidden" name="old_qty" value="<?= $row['quantity']; ?>">

                    <div class="form-group">
                        <label>Product (Batch #<?= $row['batch_id']; ?>):</label>
                        <input type="text" value="<?= $row['name']; ?>" disabled style="background:#eee;">
                    </div>

                    <div class="form-group">
                        <label>Destination Branch:</label>
                        <select name="destination" required>
                            <?php
                            $locs = mysqli_query($conn, "SELECT location_id, name FROM Location WHERE type = 'branch'");
                            while($l = mysqli_fetch_assoc($locs)){
                                $selected = ($l['location_id'] == $row['destination']) ? "selected" : "";
                                echo "<option value='".$l['location_id']."' $selected>".$l['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Quantity Moved:</label>
                        <input type="number" name="new_qty" value="<?= $row['quantity']; ?>" required min="1">
                    </div>

                    <div class="form-actions">
                        <input type="submit" value="Update Transfer" class="btn submit-btn" style="background:#f7a8b8; color:white;">
                        <a href="show_transfer.php" class="btn cancel-btn">Cancel</a>
                    </div>
                </form>
                <?php } ?>
            </div>
        </section>
    </main>
</body>
</html>