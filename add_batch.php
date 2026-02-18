<?php
require_once("auth.php");
// This ensures ONLY these two roles can even load this page
require_role(['Admin','Hub Manager', 'Hub Worker']); 

require_once("dbconnect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add New Batch - Bekari</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" /> 
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
          <li><a href="#">Orders</a></li>
        </ul>
      </nav>
    </header>
    <main>
      <section class="add_batch">
        <div class="form_container">
          <h2>Add New Production Batch</h2>
          <form class="data_form" action="insert_batch.php" method="post">
            
            <div class="form-group">
                <label for="product_id">Select Product:</label>
                <select id="product_id" name="product_id" required>
                    <option value="">-- Choose a Product --</option>
                    <?php
                    require_once('DBconnect.php');
                    // Simple query to get product names for the dropdown
                    $sql = "SELECT product_id, name FROM Products ORDER BY name ASC";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_array($result)){
                        echo "<option value='".$row['product_id']."'>".$row['name']."</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="initial_quantity">Initial Quantity:</label>
                <input type="number" id="initial_quantity" name="quantity" required min="1">
                <small style="color: #888;">Note: Current quantity will be set to this value automatically.</small>
            </div>
            
            <div class="form-group">
                <label for="made_at">Production Date & Time (Optional):</label>
                <input type="datetime-local" id="made_at" name="made_at">
                <small style="color: #888;">Leave blank to use current system time.</small>
            </div>
            
            <input type="hidden" name="status" value="0">
            
            <div class="form-actions">
                <input type="submit" class="btn submit-btn" value="Record Batch" />
                <a href="show_batches.php" class="btn cancel-btn">Cancel</a>
            </div>

          </form>
        </div>
      </section>
    </main>
  </body>
</html>