<?php
require_once("auth.php");
require_role(['Admin','Hub Manager', 'Hub Worker']); 
require 'DBconnect.php';
include('header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modify Batch - Bekari</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
      <nav>
        <div class="nav_logo"><h1><a href="index.php">Bekari</a></h1></div>
        <ul class="nav_link">
          <li><a href="show_products.php">Products</a></li>
          <li><a href="show_ingredients.php">Ingredients</a></li>
          <li><a href="show_recipes.php">Recipes</a></li>
          <li><a href="show_batches.php" class="active">Batches</a></li>
          <li><a href="show_waste.php">Waste Log</a></li>
          <li><a href="#">Orders</a></li>
        </ul>
      </nav>
    </header>
    <main>
        <section class="form_container">
            <h2>Update Batch Stock</h2>
            <?php
            require_once('DBconnect.php');

            if(isset($_GET['id'])){
                $batch_id = $_GET['id'];
                
                $sql = "SELECT b.*, p.name FROM product_batches b 
                        JOIN Products p ON b.product_id = p.product_id 
                        WHERE b.batch_id = '$batch_id'";
                $result = mysqli_query($conn, $sql);

                if($row = mysqli_fetch_array($result)){
                    // Determine status label for display only
                    if($row['status'] == 0) $current_status_label = "🟢 Fresh";
                    elseif($row['status'] == 1) $current_status_label = "🏷️ On Sale";
                    else $current_status_label = "💀 Expired";
                    ?>
                    <form action="update_batch.php" method="post" class="data_form">
                        <input type="hidden" name="batch_id" value="<?php echo $row['batch_id']; ?>">

                        <div class="form-group">
                            <label>Product Name:</label>
                            <input type="text" value="<?php echo $row['name']; ?>" disabled style="background:#eee;">
                        </div>

                        <div class="form-group">
                            <label>Current Quantity in Stock:</label>
                            <input type="number" name="new_current_quantity" value="<?php echo $row['current_quantity']; ?>" required min="0">
                        </div>

                        <div class="form-group">
                            <label>Current Status:</label>
                            <input type="text" value="<?php echo $current_status_label; ?>" disabled style="background:#eee; font-weight: bold;">
                            <small style="color: #888;">*Status is updated automatically based on batch age.*</small>
                        </div>

                        <div class="form-actions">
                            <input type="submit" value="Save Changes" class="btn submit-btn">
                            <a href="show_batches.php" class="btn cancel-btn">Cancel</a>
                        </div>
                    </form>
                    <?php
                } else {
                    echo "<p style='text-align:center; color:red;'>Batch not found.</p>";
                }
            }
            ?>
        </section>
    </main>
</body>
</html>