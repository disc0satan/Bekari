<?php
require_once("auth.php"); // Added this to ensure session data is available
// Error reporting for debugging during integration
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("DBconnect.php");
include('header.php');

$search = '';

// 1. FETCH DATA: Joins Batch_transfers with Products and Locations
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT bt.*, p.name AS product_name, l.name AS destination_name 
            FROM Batch_transfers bt
            JOIN product_batches b ON bt.batch_id = b.batch_id
            JOIN Products p ON b.product_id = p.product_id
            JOIN Location l ON bt.destination = l.location_id
            WHERE p.name LIKE '%$search%' OR l.name LIKE '%$search%'
            ORDER BY bt.ship_time DESC";
} else {
    $sql = "SELECT bt.*, p.name AS product_name, l.name AS destination_name 
            FROM Batch_transfers bt
            JOIN product_batches b ON bt.batch_id = b.batch_id
            JOIN Products p ON b.product_id = p.product_id
            JOIN Location l ON bt.destination = l.location_id
            ORDER BY bt.ship_time DESC";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Transfer Log - Bekari</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" /> 
</head>
<body>
    <main>
        <section class="batches_list">
            <div class="list_box">
                <h1>Batch Transfer History</h1>
                
                <div class="search-and-add">
                    <?php if (in_array($_SESSION['role'], ['Admin', 'Hub Manager', 'Hub Worker'])): ?>
                        <a href="add_transfer.php" class="btn add-new" title="New Transfer">+</a>
                    <?php endif; ?>
                    
                    <form action="show_transfer.php" method="GET" class="search-form">
                        <input type="text" name="search" placeholder="Search Product or Branch..." 
                               value="<?php echo htmlspecialchars($search); ?>" class="search-input">
                        <button type="submit" class="btn submit-btn">Search</button>
                    </form>
                </div>

                <table class="data_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ship Time</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Destination</th>
                            <th>Batch Ref</th>
                            <th>Actions</th> </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_array($result)){
                        ?>
                        <tr>
                            <td>#<?php echo $row['batch_transfer_id']; ?></td>
                            <td><?php echo date('d M, Y - h:i A', strtotime($row['ship_time'])); ?></td>
                            <td><strong><?php echo $row['product_name']; ?></strong></td>
                            <td style="color: #e67e22; font-weight: bold;"><?php echo $row['quantity']; ?> units</td>
                            <td><span style="background: #f1f1f1; padding: 4px 8px; border-radius: 4px;"><?php echo $row['destination_name']; ?></span></td>
                            <td>Batch #<?php echo $row['batch_id']; ?></td>

                            <td class="action-links">
                                <?php 
                                // Added role check for Edit/Delete actions
                                if (in_array($_SESSION['role'], ['Admin','Hub Manager', 'Hub Worker'])): 
                                ?>
                                     <a href="modify_transfer.php?id=<?php echo $row['batch_transfer_id']; ?>" class="action-btn update">Edit</a>
                                     <a href="delete_transfer.php?id=<?php echo $row['batch_transfer_id']; ?>" class="action-btn delete" onclick="return confirm('Revert this transfer and return stock to central batch?');">Delete</a>
                                <?php else: ?>
                                    <span style="color:#999; font-style: italic;">View only</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align:center;'>No transfer records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>