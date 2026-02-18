<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modify Ingredient - Bekari</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" /> 
</head>
<body>
    <main>
        <section class="modify_ingredient">
            <div class="form_container">
                <h2>Update Ingredient Details</h2>
                
                <?php
                require_once('DBconnect.php'); 

                // 1. Check if the 'id' was passed in the URL
                if(isset($_GET['id'])){
                    $ingredient_id = $_GET['id'];
                    
                    // 2. Fetch the current details using the simple demo style
                    $sql = "SELECT * FROM Ingredients WHERE ingredient_id = '$ingredient_id'";
                    $result = mysqli_query($conn, $sql);

                    // 3. Check if an ingredient was found
                    if($row = mysqli_fetch_array($result)){
                        // Ingredient found: Display the form
                        ?>
                        <form class="data_form" action="update_ingredient.php" method="post">
                            
                            <input type="hidden" name="ingredient_id" value="<?php echo $row['ingredient_id']; ?>">
                            
                            <div class="form-group">
                                <label for="name">Ingredient Name:</label>
                                <input type="text" id="name" name="new_name" value="<?php echo $row['name']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="measure_unit">Measurement Unit:</label>
                                <input type="text" id="measure_unit" name="new_measure_unit" value="<?php echo $row['measure_unit']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="current_stock">Current Stock Quantity:</label>
                                <input type="number" step="0.01" id="current_stock" name="new_current_stock" value="<?php echo $row['current_stock']; ?>" required min="0.00">
                            </div>

                            <div class="form-group">
                                <label for="reorder_point">Reorder Point:</label>
                                <input type="number" step="0.01" id="reorder_point" name="new_reorder_point" value="<?php echo $row['reorder_point']; ?>" required min="0.00">
                            </div>

                            <div class="form-actions">
                                <input type="submit" class="btn submit-btn" value="Update Ingredient">
                                <a href="show_ingredients.php" class="btn cancel-btn">Cancel</a>
                            </div>
                        </form>
                        <?php

                    } else {
                        echo "Ingredient not found.";
                    }
                } else {
                    header("Location: show_ingredients.php");
                }
                ?>
            </div>
        </section>
    </main>
</body>
</html>