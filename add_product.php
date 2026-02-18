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
    <title>Add New Product - Bekari</title>
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
          <li><a href="show_products.php" class="active">Products</a></li>
          <li><a href="show_ingredients.php">Ingredients</a></li>
          <li><a href="show_recipes.php">Recipes</a></li>
          <li><a href="show_batches.php">Batches</a></li>
          <li><a href="#">Orders</a></li>
        </ul>
      </nav>
    </header>
    <main>
      <section class="add_product">
        <div class="form_container">
          <h2>Add New Product</h2>
          <form class="data_form" action="insert_product.php" method="post">
            
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="new_product_name" required>
            </div>
            
            <div class="form-group">
                <label for="base_price">Base Price (BDT):</label>
                <input type="number" step="0.01" id="base_price" name="new_base_price" required min="0.01">
            </div>
            
            <div class="form-group">
                <label for="shelf_life">Shelf Life (Days):</label>
                <input type="number" id="shelf_life" name="new_shelf_life" required min="1">
            </div>
            
            <div class="form-group">
                <label for="markdown_hours">Markdown Hours:</label>
                <input type="number" id="markdown_hours" name="new_markdown_hours" required min="0">
            </div>
            
            <div class="form-group">
                <label for="discount_pct">Discount Percentage (%):</label>
                <input type="number" step="0.01" id="discount_pct" name="new_discount_pct" required min="0" max="100">
            </div>
            
            <div class="form-actions">
                <input type="submit" class="btn submit-btn" value="Add Product" />
                <a href="show_products.php" class="btn cancel-btn">Cancel</a>
            </div>

          </form>
        </div>
      </section>
    </main>
  </body>
</html>