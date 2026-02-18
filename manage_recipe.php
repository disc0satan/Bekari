<?php
require_once("auth.php");
require_role(['Admin','Hub Manager', 'Hub Worker']); 
require 'DBconnect.php';
include('header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Recipe - Bekari</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" /> 
    <style>
        /* Custom styles to fix the alignment of the mini-form */
        .recipe-add-box {
            max-width: 600px;
            margin: 20px auto;
            padding: 25px;
            border: 2px dashed #ffc0cb;
            border-radius: 12px;
            background-color: rgba(255, 192, 203, 0.05);
        }
        .compact-row {
            display: flex;
            gap: 15px;
            align-items: flex-end; /* Aligns labels and inputs at the bottom */
            margin-bottom: 20px;
            text-align: left;
        }
        .compact-group {
            flex: 1; /* Allows groups to grow equally */
            display: flex;
            flex-direction: column;
        }
        .compact-group label {
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .compact-group select, 
        .compact-group input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box; /* Ensures padding doesn't break width */
        }
    </style>
</head>
<body>
    <main>
        <section class="recipe_manager">
            <div class="list_box">
                <?php
                require_once("DBconnect.php"); 

                if (isset($_GET['product_id'])) {
                    $product_id = $_GET['product_id'];

                    $product_sql = "SELECT name FROM Products WHERE product_id = '$product_id'";
                    $product_result = mysqli_query($conn, $product_sql);

                    if ($product_row = mysqli_fetch_array($product_result)) {
                        $product_name = $product_row['name'];
                        ?>
                        
                        <h2 style="margin-bottom: 5px;">Recipe for: <?php echo $product_name; ?></h2>
                        <p style="color: #888; margin-bottom: 25px;">Product ID: #<?php echo $product_id; ?></p>
                        
                        <h3>Current Ingredients Used</h3>

                        <?php
                        $recipe_sql = "SELECT u.product_id, u.ingredient_id, u.quantity, i.name AS ingredient_name, i.measure_unit 
                                       FROM Used_in u
                                       JOIN Ingredients i ON u.ingredient_id = i.ingredient_id
                                       WHERE u.product_id = '$product_id'";
                        
                        $recipe_result = mysqli_query($conn, $recipe_sql);

                        if (mysqli_num_rows($recipe_result) > 0) {
                            ?>
                            <table class="data_table">
                                <thead>
                                    <tr>
                                        <th>Ingredient Name</th>
                                        <th>Required Quantity</th>
                                        <th>Unit</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_array($recipe_result)) {
                                        ?>
                                        <tr>
                                            <td><strong><?php echo $row["ingredient_name"]; ?></strong></td>
                                            <td><?php echo number_format($row["quantity"], 4); ?></td>
                                            <td><?php echo $row["measure_unit"]; ?></td>
                                            <td class="action-links">
                                                <a href="delete_recipe_item.php?product_id=<?php echo $row['product_id']; ?>&ingredient_id=<?php echo $row['ingredient_id']; ?>" 
                                                   class="action-btn delete" 
                                                   onclick="return confirm('Remove ingredient?');">Remove</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        } else {
                            echo '<p style="text-align: center; color: #888; padding: 20px; border: 1px solid #eee; border-radius: 8px;">No ingredients defined in this recipe yet.</p>';
                        }
                        ?>

                        <div class="recipe-add-box">
                            <h3 style="margin-top: 0;">Add New Ingredient</h3>
                            <form action="insert_recipe_item.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                
                                <div class="compact-row">
                                    <div class="compact-group" style="flex: 2;">
                                        <label for="ingredient_id">Select Ingredient</label>
                                        <select id="ingredient_id" name="ingredient_id" required>
                                            <option value="">-- Choose --</option>
                                            <?php
                                            $ingredients_sql = "SELECT ingredient_id, name, measure_unit FROM Ingredients";
                                            $ingredients_res = mysqli_query($conn, $ingredients_sql);
                                            while ($ing = mysqli_fetch_array($ingredients_res)) {
                                                echo '<option value="' . $ing['ingredient_id'] . '">' . $ing['name'] . ' (' . $ing['measure_unit'] . ')</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="compact-group" style="flex: 1;">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" id="quantity" step="0.0001" name="quantity" placeholder="0.0000" required min="0.0001">
                                    </div>
                                </div>
                                
                                <div class="form-actions" style="border-top: 1px solid #eee; padding-top: 20px; margin-top: 10px;">
                                    <input type="submit" class="btn submit-btn" value="Add to Recipe" />
                                    <a href="show_recipes.php" class="btn cancel-btn">Back to List</a>
                                </div>
                            </form>
                        </div>

                    <?php
                    } else {
                        echo "Product not found.";
                    }
                } else {
                    header("Location: show_recipes.php");
                }
                ?>
            </div>
        </section>
    </main>
</body>
</html>