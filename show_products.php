<?php 
require_once("auth.php");
include('header.php'); // Ensure session is active for role checking
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Products List - Bekari</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <main>
      <section class="products_list">
        <div class="list_box">
          <h1>Products List</h1>

          <div class="search-and-add">
            <?php if (in_array($_SESSION['role'], ['Admin', 'Hub Manager', 'Hub Worker'])): ?>
                <a href="add_product.php" class="btn add-new">+</a>
            <?php endif; ?>
          
            <form action="show_products.php" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search by name or ID..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="search-input">
                <button type="submit" class="btn submit-btn" style="padding: 10px 15px;">Search</button>
            </form>
          </div>

          <h5 style="text-align: center; color: #f8a7b9; font-weight: 600;">Click on the Product Name to View/Edit it's Recipe!</h5>

          <table class="data_table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Base Price (BDT)</th>
                <th>Shelf Life</th>
                <th>Markdown</th>
                <th>Discount</th>
                <th>Actions</th> 
              </tr>
            </thead>
            <tbody>
              <?php
              require_once('DBconnect.php');

              // 2. Build the basic SQL query
              $sql = "SELECT * FROM Products";

              // 3. Simple Search logic
              if (isset($_GET['search']) && !empty($_GET['search'])) {
                  $search = mysqli_real_escape_string($conn, $_GET['search']);
                  $sql = "SELECT * FROM Products WHERE name LIKE '%$search%' OR product_id LIKE '%$search%'";
              }

              // 4. Run the query
              $result = mysqli_query($conn, $sql);

              if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_array($result)) {
              ?>
              <tr>
                <td><?php echo $row['product_id']; ?></td>
                <td>
                    <a href="manage_recipe.php?product_id=<?php echo $row['product_id']; ?>" class="recipe-link">
                        <?php echo $row['name']; ?>
                    </a>
                </td>
                <td><?php echo number_format($row['base_price'], 2); ?></td>
                <td><?php echo $row['shelf_life']; ?> Days</td>
                <td><?php echo $row['markdown_hours']; ?> Hrs</td>
                <td><?php echo number_format($row['discount_pct'], 2); ?>%</td>
                <td class="action-links">
                  <?php 
                  // Access Control for Edit and Delete buttons
                  if (in_array($_SESSION['role'], ['Admin','Hub Manager', 'Hub Worker'])): 
                  ?>
                    <a href="modify_product.php?id=<?php echo $row['product_id']; ?>" class="action-btn update">Edit</a>
                    <a href="delete_product.php?id=<?php echo $row['product_id']; ?>" class="action-btn delete" onclick="return confirm('Delete this product?');">Delete</a>
                  <?php else: ?>
                    <span style="color:#999; font-style: italic;">View only</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php
                  }
              } else {
                  echo "<tr><td colspan='7' style='text-align:center;'>No products found.</td></tr>";
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