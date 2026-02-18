<?php
require_once("auth.php");
// This ensures ONLY these two roles can even load this page
require_role(['Admin','Hub Manager', 'Hub Worker']); 

require_once("dbconnect.php");
// ... rest of your edit logic
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Batch Transfer - Bekari</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" /> 
    <style>
        /* Specific alignment for the transfer form row to match your project aesthetic */
        .compact-row { 
            display: flex; 
            gap: 15px; 
            align-items: flex-end; 
            margin-bottom: 20px; 
        }
        .compact-group { 
            display: flex; 
            flex-direction: column; 
            flex: 1;
        }
    </style>
</head>
<body>
    <header>
      <nav>
        <div class="nav_logo">
          <h1><a href="index.php">Bekari</a></h1>
        </div>
        <ul class="nav_link">
          <li><a href="show_products.php">Products</a></li>
          <li><a href="show_ingredients.php">Ingredients</a></li>
          <li><a href="show_recipes.php">Recipes</a></li>
          <li><a href="show_batches.php" class="active">Batches</a></li>
          <li><a href="show_transfer.php">Transfer Log</a></li>
        </ul>
      </nav>
    </header>
    <main>
      <section class="add_batch">
        <div class="form_container">
          <h2>New Batch Transfer</h2>
          <form class="data_form" action="insert_transfer.php" method="post">
            
            <div class="form-group">
                <label for="batch_id">Select Production Batch (Central Kitchen):</label>
                <select id="batch_id" name="batch_id" required>
                    <option value="">-- Choose a Batch --</option>
                    <?php
                    // Fetch batches that have stock and are not expired
                    $sql = "SELECT b.batch_id, p.name, b.current_quantity 
                            FROM product_batches b 
                            JOIN Products p ON b.product_id = p.product_id 
                            WHERE b.current_quantity > 0 AND b.status < 2
                            ORDER BY b.made_timestamp DESC";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_array($result)){
                        echo "<option value='".$row['batch_id']."'>".$row['name']." (#".$row['batch_id'].") - Available: ".$row['current_quantity']."</option>";
                    }
                    ?>
                </select>
                <small style="color: #888;">Only fresh/discounted batches with stock are shown.</small>
            </div>

            <div class="compact-row">
                <div class="compact-group" style="flex: 2;">
                    <label for="destination">Destination Branch:</label>
                    <select id="destination" name="destination" required>
                        <option value="">-- Select Branch --</option>
                        <?php
                        $loc_sql = "SELECT location_id, name FROM Location WHERE type = 'branch' ORDER BY name ASC";
                        $loc_res = mysqli_query($conn, $loc_sql);
                        while($l = mysqli_fetch_array($loc_res)) {
                            echo "<option value='".$l['location_id']."'>".$l['name']."</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="compact-group" style="flex: 1;">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" required min="1">
                </div>
            </div>
            
            <div class="form-actions">
                <input type="submit" name="submit_transfer" class="btn submit-btn" value="Ship Items" />
                <a href="show_batches.php" class="btn cancel-btn">Cancel</a>
            </div>

          </form>
        </div>
      </section>
    </main>
  </body>
</html>