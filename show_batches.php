<?php
require_once("auth.php"); // Ensure session is active
require_once("DBconnect.php");
include('header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="refresh" content="60">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Batches - Bekari</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" /> 
</head>
<body>
    <main>
        <section class="batches_list">
            <div class="list_box">
                <h1>Product Batches</h1>
                
                <div class="search-and-add">
                    <?php if (in_array($_SESSION['role'], ['Admin', 'Hub Manager', 'Hub Worker'])): ?>
                        <a href="add_batch.php" class="btn add-new">+</a>
                    <?php endif; ?>

                    <form action="show_batches.php" method="GET" class="search-form">
                        <input type="text" name="search" placeholder="Search ID or Product..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="search-input">
                        <button type="submit" class="btn submit-btn">Search</button>
                    </form>
                </div>

                <table class="data_table">
                    <thead>
                        <tr>
                            <th>Batch ID</th>
                            <th>Product Name</th>
                            <th>Stock (C/I)</th>
                            <th>Status</th>
                            <th>Until Discount</th>
                            <th>Until Discard</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        function formatBakeryTime($hours) {
                            if ($hours <= 0) return "0 mins";
                            if ($hours < 1) {
                                return round($hours * 60) . " mins";
                            }
                            return round($hours, 1) . " hrs";
                        }

                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                            $search = mysqli_real_escape_string($conn, $_GET['search']);
                            $sql = "SELECT b.*, p.name, p.shelf_life, p.markdown_hours 
                                    FROM product_batches b 
                                    JOIN Products p ON b.product_id = p.product_id 
                                    WHERE b.batch_id LIKE '%$search%' OR p.name LIKE '%$search%'
                                    ORDER BY b.made_timestamp DESC";
                        } else {
                            $sql = "SELECT b.*, p.name, p.shelf_life, p.markdown_hours 
                                    FROM product_batches b 
                                    JOIN Products p ON b.product_id = p.product_id 
                                    ORDER BY b.made_timestamp DESC";
                        }
                        
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_array($result)){
                                $made_at = strtotime($row['made_timestamp']);
                                $now = time();
                                $hours_passed = ($now - $made_at) / 3600;
                                
                                $total_shelf_hours = $row['shelf_life'] * 24;
                                $discard_remaining = $total_shelf_hours - $hours_passed;
                                $discount_remaining = ($total_shelf_hours - $row['markdown_hours']) - $hours_passed;

                                if ($row['status'] == 2) {
                                    $status_label = "<b style='color: #666;'>EXPIRED</b>";
                                    $row_style = "style='background-color: #f2f2f2; color: #999;'";
                                    $discount_text = "---";
                                    $discard_text = "DISCARD NOW";
                                } elseif ($row['status'] == 1) {
                                    $status_label = "<b style='color: #e67e22;'>ON SALE</b>";
                                    $row_style = "style='background-color: #fffaf0;'";
                                    $discount_text = "PASSED";
                                    $discard_text = formatBakeryTime($discard_remaining);
                                } else {
                                    $status_label = "<b style='color: #27ae60;'>FRESH</b>";
                                    $row_style = "";
                                    $discount_text = formatBakeryTime($discount_remaining);
                                    $discard_text = formatBakeryTime($discard_remaining);
                                }
                        ?>
                        <tr <?php echo $row_style; ?>>
                            <td><?php echo $row['batch_id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['current_quantity'] . " / " . $row['initial_quantity']; ?></td>
                            <td><?php echo $status_label; ?></td>
                            <td><?php echo $discount_text; ?></td>
                            <td style="font-weight: bold;"><?php echo $discard_text; ?></td>
                            <td class="action-links">
                                <?php 
                                // Access Control for Edit/Delete Buttons
                                if (in_array($_SESSION['role'], ['Admin','Hub Manager', 'Hub Worker'])): 
                                ?>
                                    <a href="modify_batch.php?id=<?php echo $row['batch_id']; ?>" class="action-btn update">Edit</a>
                                    <a href="delete_batch.php?id=<?php echo $row['batch_id']; ?>" class="action-btn delete" onclick="return confirm('Delete record?');">Delete</a>
                                <?php else: ?>
                                    <span style="color:#999; font-style: italic;">View only</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align:center;'>No records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>