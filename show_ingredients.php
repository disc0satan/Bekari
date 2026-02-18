<?php
require_once("auth.php"); // Ensure session is active
require_once("DBconnect.php");
include('header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ingredients - Bekari</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" /> 
</head>
<body>
    
    <main>
        <section class="ingredients">
            <div class="list_box">
                <h1>Ingredients List</h1>
                
                <div class="search-and-add">
                    <a href="add_ingredient.php" class="btn add-new">+</a>
                    <form action="show_ingredients.php" method="GET" class="search-form">
                        <input type="text" name="search" placeholder="Search by name or ID..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" class="search-input">
                        <button type="submit" class="btn submit-btn">Search</button>
                    </form>
                </div>

                <table class="data_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ingredient Name</th> <th>Current Stock</th>
                            <th>Reorder Point</th>
                            <th>Last Updated</th> 
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once("DBconnect.php"); 

                        $sql = "SELECT * FROM Ingredients";

                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                            $search = $_GET['search'];
                            $sql = "SELECT * FROM Ingredients WHERE name LIKE '%$search%' OR ingredient_id LIKE '%$search%'";
                        }

                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                
                                // INTEGRATED STATUS LOGIC
                                // If stock is low, we'll create a special CSS style and add the icon
                                if ($row['current_stock'] <= $row['reorder_point']) {
                                    $name_style = 'style="color: #e74c3c; font-weight: bold;"'; // Red and Bold
                                    $alert_icon = ' ❗';
                                } else {
                                    $name_style = ''; // Standard look
                                    $alert_icon = '';
                                }
                        ?>
                        <tr>
                            <td><?php echo $row["ingredient_id"]; ?></td>
                            <td <?php echo $name_style; ?>>
                                <?php echo $row["name"] . $alert_icon; ?>
                            </td>
                            <td><?php echo $row["current_stock"] . " " . $row["measure_unit"]; ?></td>
                            <td><?php echo $row["reorder_point"]; ?></td>
                            <td><?php echo $row["last_updated"]; ?></td> 
                            <td class="action-links">
    <?php 
    // Define who is allowed to edit ingredients
    $can_edit = ($_SESSION['role'] === 'Hub Manager' ||$_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Hub Worker');
    
    if ($can_edit): ?>
        <a href="modify_ingredient.php?id=<?= $row['ingredient_id']; ?>" class="action-btn update">Edit</a>
        
        <a href="delete_ingredient.php?id=<?= $row['ingredient_id']; ?>" 
           class="action-btn delete" 
           onclick="return confirm('Remove this ingredient?');">
           Delete
        </a>
    <?php else: ?>
        <span style="color:#999; font-style: italic;">View only</span>
    <?php endif; ?>
</td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align:center;'>No ingredients found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>