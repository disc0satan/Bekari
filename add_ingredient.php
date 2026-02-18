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
    <title>Add New Ingredient - Bekari</title>
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
          <li><a href="show_ingredients.php" class="active">Ingredients</a></li>
          <li><a href="show_recipes.php">Recipes</a></li>
          <li><a href="show_batches.php">Batches</a></li>
          <li><a href="#">Orders</a></li>
        </ul>
      </nav>
    </header>
    <main>
      <section class="add_ingredient">
        <div class="form_container">
          <h2>Add New Ingredient</h2>
          <form class="data_form" action="insert_ingredient.php" method="post">
            
            <div class="form-group">
                <label for="name">Ingredient Name:</label>
                <input type="text" id="name" name="new_name" required>
            </div>
            
            <div class="form-group">
                <label for="measure_unit">Measurement Unit (e.g., grams, liters):</label>
                <input type="text" id="measure_unit" name="new_measure_unit" required>
            </div>
            
            <div class="form-group">
                <label for="current_stock">Initial Stock Quantity:</label>
                <input type="number" step="0.01" id="current_stock" name="new_current_stock" required min="0.00">
            </div>
            
            <div class="form-group">
                <label for="reorder_point">Reorder Point (Quantity to reorder at):</label>
                <input type="number" step="0.01" id="reorder_point" name="new_reorder_point" required min="0.00">
            </div>
            
            <div class="form-actions">
                <input type="submit" class="btn submit-btn" value="Add Ingredient" />
                <a href="show_ingredients.php" class="btn cancel-btn">Cancel</a>
            </div>

          </form>
        </div>
      </section>
    </main>
  </body>
</html>