<?php
require_once("auth.php");
require_role(['Admin','Hub Manager', 'Hub Worker']); 
require_once("dbconnect.php");
include('header.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Recipes - Bekari</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" /> 
</head>
<body>
    <main>
        <section class="recipes_list">
            <div class="list_box">
                <h1>Recipes</h1>
                
                <div class="search-and-add" style="justify-content: flex-end;">
                    <form action="show_recipes.php" method="GET" class="search-form">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Search by name or ID..." 
                            value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" 
                            class="search-input"
                        >
                        <button type="submit" class="btn submit-btn" style="padding: 10px 15px;">Search</button>
                    </form>
                </div> 

                <table class="data_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once("DBconnect.php"); 

                        // 1. Build the basic SQL query
                        $sql = "SELECT * FROM Products";

                        // 2. Simple Search logic (Demo Style)
                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                            $search = $_GET['search'];
                            $sql = "SELECT * FROM Products WHERE name LIKE '%$search%' OR product_id LIKE '%$search%'";
                        }

                        // 3. Run the query using mysqli_query
                        $result = mysqli_query($conn, $sql);

                        // 4. Check results and loop
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row["product_id"]; ?></td>
                            <td><?php echo $row["name"]; ?></td>
                            <td class="action-links">
                                <a href="manage_recipe.php?product_id=<?php echo $row['product_id']; ?>" 
                                   class="action-btn update" 
                                   style="font-weight: 700;">View/Edit Recipe</a>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='3' style='text-align:center;'>No products found matching your criteria.</td></tr>";
                        }
                        
                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>