<?php
require_once("auth.php");
require_role(['Admin','Hub Manager', 'Hub Worker']); 
require_once("DBconnect.php");


// Fetch total waste grouped by product
$sql = "SELECT p.product_id, p.name, SUM(b.current_quantity) as total_wasted, COUNT(b.batch_id) as batch_count
        FROM product_batches b 
        JOIN Products p ON b.product_id = p.product_id 
        WHERE b.status = 2 
        GROUP BY p.product_id, p.name
        ORDER BY total_wasted DESC";

$result = mysqli_query($conn, $sql);

// Calculate Grand Total for the summary box
$total_query = mysqli_query($conn, "SELECT SUM(current_quantity) as grand_total FROM product_batches WHERE status = 2");
$total_row = mysqli_fetch_assoc($total_query);
$grand_total = $total_row['grand_total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Waste Analysis - Bekari</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" /> 
</head>
<body>
    <header>
      <nav>
        <div class="nav_logo"><h1><a href="index.php">Bekari</a></h1></div>
        <ul class="nav_link">
          <li><a href="show_batches.php">Batches</a></li>
          <li><a href="show_waste.php" class="active">Waste Log</a></li>
        </ul>
      </nav>
    </header>
    <main>
        <section class="batches_list">
            <div class="list_box">
                <h1>Bakery Waste Analysis</h1>
                
                <div style="background: #fdf2f2; border-left: 5px solid #e74c3c; padding: 20px; margin-bottom: 20px; border-radius: 5px;">
                    <h3 style="color: #c0392b; margin: 0;">Total Unsold Items Wasted: <?php echo $grand_total; ?> units</h3>
                    <p style="margin: 5px 0 0 0; color: #666;">Summary of all expired batches currently in the system.</p>
                </div>

                <table class="data_table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Total Quantity Wasted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_array($result)){
                        ?>
                        <tr>
                            <td><?php echo $row['product_id']; ?></td>
                            <td><strong><?php echo $row['name']; ?></strong></td>
                            <td style="color: #e74c3c; font-weight: bold;"><?php echo $row['total_wasted']; ?> units</td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='4' style='text-align:center;'>Excellent! No waste recorded in the system.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <div style="margin-top: 20px;">
                    <a href="show_batches.php" class="btn submit-btn" style="text-decoration:none;">Back to All Batches</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>