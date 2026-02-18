<?php
require_once("auth.php");
require_role(['Admin','Branch Manager', 'Branch Worker']); 
require 'DBconnect.php';
include('header.php');
// Initialize variable
$search_term = "";
$sql = "SELECT * FROM Customers ORDER BY customer_id DESC"; 

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = mysqli_real_escape_string($conn, $_GET['search']);
    // Search by ID OR Phone Number
    $sql = "SELECT * FROM Customers 
            WHERE customer_id = '$search_term' 
            OR phone_number LIKE '%$search_term%'";
}

$result = mysqli_query($conn, $sql);
$customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Directory - Bekari</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" /> 
    
    <script>
        function confirmDelete(id) {
            return confirm("Are you sure you want to delete Customer ID #" + id + "? This cannot be undone.");
        }
    </script>
</head>
<body>

    <main>
        <div class="list_box">
            
            <h2>Customer Directory</h2>

            <div class="search-and-add">
                
                <div>
                    <a href="customers.php" class="btn add-new">+ Add New Customer</a>
                    
                    
                </div>

                <form method="GET" action="customer_list.php" class="search-form">
                    <input type="text" name="search" class="search-input" placeholder="Search ID or Phone..." value="<?= htmlspecialchars($search_term) ?>">
                    <button type="submit" class="btn" style="background: #333; color: white;">Search</button>
                    
                    <?php if($search_term): ?>
                        <a href="customer_list.php" class="btn" style="background: #ccc; color: #333; margin-left: 5px;">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <table class="data_table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Points</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($customers) > 0): ?>
                        <?php foreach ($customers as $c): ?>
                        <tr>
                            <td>#<?= $c['customer_id'] ?></td>
                            <td style="font-weight: 500; color: #333;"><?= htmlspecialchars($c['name']) ?></td>
                            <td><?= htmlspecialchars($c['phone_number']) ?></td>
                            <td><?= htmlspecialchars($c['email']) ?></td>
                            <td><span style="background: #fff0f3; color: #d63384; padding: 3px 8px; border-radius: 10px; font-weight: bold;"><?= $c['points'] ?> pts</span></td>
                            
                            <td class="action-links">
                                <a href="edit_customer.php?id=<?= $c['customer_id'] ?>" class="btn update">Edit</a>
                                <a href="delete_customer.php?id=<?= $c['customer_id'] ?>" class="btn delete" onclick="return confirmDelete(<?= $c['customer_id'] ?>)">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #777;">
                                No customers found matching your search.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </main>

</body>
</html>