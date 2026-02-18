<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Update Product - Bekari</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" /> 
</head>
<body>
    <main>
        <section class="modify_product">
            <div class="form_container">
                <h2>Update Product Details</h2>
                
                <?php
                require_once('DBconnect.php'); 

                // 1. Check if the 'id' was passed in the URL
                if(isset($_GET['id'])){
                    $product_id = $_GET['id'];
                    
                    // 2. Fetch the current product details using the simple demo style
                    $sql = "SELECT * FROM Products WHERE product_id = '$product_id'";
                    $result = mysqli_query($conn, $sql);

                    // 3. Check if a product was found
                    if($row = mysqli_fetch_array($result)){
                        // Product found: Display the form
                        ?>
                        <form class="data_form" action="update_product.php" method="post">
                            
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            
                            <div class="form-group">
                                <label for="product_name">Product Name:</label>
                                <input type="text" id="product_name" name="new_product_name" value="<?php echo $row['name']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="base_price">Base Price (BDT):</label>
                                <input type="number" step="0.01" id="base_price" name="new_base_price" value="<?php echo $row['base_price']; ?>" required min="0.01">
                            </div>

                            <div class="form-group">
                                <label for="shelf_life">Shelf Life (Days):</label>
                                <input type="number" id="shelf_life" name="new_shelf_life" value="<?php echo $row['shelf_life']; ?>" required min="1">
                            </div>

                            <div class="form-group">
                                <label for="markdown_hours">Markdown Hours:</label>
                                <input type="number" id="markdown_hours" name="new_markdown_hours" value="<?php echo $row['markdown_hours']; ?>" min="0">
                            </div>

                            <div class="form-group">
                                <label for="discount_pct">Discount Percentage (%):</label>
                                <input type="number" step="0.01" id="discount_pct" name="new_discount_pct" value="<?php echo $row['discount_pct']; ?>" min="0" max="100">
                            </div>

                            <div class="form-actions">
                                <input type="submit" class="btn submit-btn" value="Update Product">
                                <a href="show_products.php" class="btn cancel-btn">Cancel</a>
                            </div>
                        </form>
                        <?php

                    } else {
                        echo "Product not found.";
                    }
                } else {
                    header("Location: show_products.php");
                }
                ?>
            </div>
        </section>
    </main>
</body>
</html>