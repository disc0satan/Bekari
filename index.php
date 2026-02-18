<?php
session_start();
require_once("dbconnect.php");

// 1. Authentication Check
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: home.php");
    exit();
}

$role = $_SESSION['role']; // Assuming roles are 'Admin', 'Branch', 'Hub'
$first_name = $_SESSION['first_name'];
$employee_id = $_SESSION['employee_id'];
$employee_location = $_SESSION['location_id'];

// 2. Notification Count Logic
$sql = "SELECT COUNT(*) as unread_count FROM notifications 
        WHERE role = '$role' 
        AND (branch_id IS NULL OR branch_id = '$employee_location') 
        AND read_status = 0";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$unread_count = $row['unread_count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bekari Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Existing Styles + Layout Improvements */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #fff5f7; }
        
        nav { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 15px 30px; 
            background: white; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .nav_logo h1 a { font-family: 'Dancing Script', cursive; color: #f8a7b9; text-decoration: none; font-size: 2rem; }
        
        .nav_right { display: flex; align-items: center; gap: 20px; }

        .panel_section { margin-bottom: 40px; padding: 0 40px; }
        .panel_title { color: #e66a92; border-bottom: 2px solid #f8a7b9; display: inline-block; margin-bottom: 20px; font-size: 1.2rem; }

        .dashboard_grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        /* Large Action Buttons */
        .btn-large {
            background-color: #f8a7b9;
            color: white !important;
            font-size: 1.2rem;
            padding: 30px 20px;
            border-radius: 12px;
            text-decoration: none;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(248, 167, 185, 0.3);
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .btn-large:hover { transform: scale(1.05); background-color: #e66a92; }

        /* Secondary Small Buttons */
        .btn-small {
            background-color: white;
            color: #f8a7b9;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            border: 1px solid #f8a7b9;
            font-weight: 600;
            transition: 0.2s;
        }
        .btn-small:hover { background-color: #f8a7b9; color: white; }

        .logout-btn { background: #f8a7b9; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>

<header>
    <nav>
        <div class="nav_logo">
            <h1><a href="index.php">Bekari</a></h1>
        </div>
        
        <div class="nav_right">
            <a href="notifications.php" style="text-decoration:none; color:#f8a7b9; font-size:1.2rem;">
                🔔 <?php if($unread_count>0){ echo "($unread_count)"; } ?>
            </a>
            <span>Welcome, <strong><?php echo $first_name; ?></strong></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>
</header>

<main>

    <?php if ($role == 'Admin'): ?>
        <section class="panel_section">
            <h3 class="panel_title">Admin Panel</h3>
            <div class="dashboard_grid">
                <a href="branch_stock.php" class="btn-large">Branchwise Stock</a>
                <a href="sales_report.php" class="btn-large">📊 Production Reports</a>
                <a href="production_report.php" class="btn-large">📊 Sales Reports</a>
                <a href="show_employee.php" class="btn-large">👥Employees</a>
                <a href="show_locations.php" class="btn-large">📍Locations</a>
            </div>
        </section>

        <section class="panel_section">
            <h3 class="panel_title">Inventory & Operations</h3>
            <div class="dashboard_grid">
                <a href="show_products.php" class="btn-small">Products</a>
                <a href="show_ingredients.php" class="btn-small">Ingredients</a>
                <a href="show_recipes.php" class="btn-small">Recipes</a>
                <a href="show_batches.php" class="btn-small">Batches</a>
                <a href="show_transfer.php" class="btn-small">Transfers</a>
                <a href="show_waste.php" class="btn-small">Waste Log</a>
                <a href="show_orders.php" class="btn-small">Orders</a>
                <a href="customer_list.php" class="btn-small">Customers</a>
            </div>
        </section>

    <?php elseif ($role == 'Branch Worker'): ?>
        <section class="panel_section">
            <h3 class="panel_title">Others</h3>
            <div class="dashboard_grid">
                <a href="show_locations.php" class="btn-small">Locations</a>
                <a href="show_employee.php" class="btn-small">Employees</a>
            </div>
        </section>

        <section class="panel_section">
            <h3 class="panel_title">Store Operations</h3>
            <div class="dashboard_grid">
                <a href="create_order.php" class="btn-large" style="background:#e66a92;">🛒 NEW ORDER</a>
                <a href="show_orders.php" class="btn-large">📜 Order History</a>
                <a href="customer_list.php" class="btn-large">👥 Customers</a>
            </div>
        </section>

        <section class="panel_section">
            <h3 class="panel_title">Resources</h3>
            <div class="dashboard_grid">
                <a href="branch_stock.php" class="btn-small">Branch Stock</a>
                <a href="show_products.php" class="btn-small">Product List</a>
                <a href="show_batches.php" class="btn-small">Batches</a>
                <a href="show_transfer.php" class="btn-small">Receive Transfers</a>
            </div>
        </section>

    <?php elseif ($role == 'Hub Worker'): ?>
        <section class="panel_section">
            <h3 class="panel_title">Others</h3>
            <div class="dashboard_grid">
                <a href="show_employee.php" class="btn-small">Employees</a>
                <a href="show_locations.php" class="btn-small">Locations</a>
            </div>
        </section>
        <section class="panel_section">
            <h3 class="panel_title">Production Management</h3>
            <div class="dashboard_grid">
                <a href="production_report.php" class="btn-large">📈 Production Report</a>
                <a href="show_batches.php" class="btn-large">🥣 Product Batches</a>
                <a href="show_transfer.php" class="btn-large">🚚 Batch Transfers</a>
                <a href="show_waste.php" class="btn-large">🗑️ Waste Log</a>
            </div>
        </section>

        <section class="panel_section">
            <h3 class="panel_title">Inventory & Specs</h3>
            <div class="dashboard_grid">
                <a href="show_products.php" class="btn-small">Products</a>
                <a href="show_ingredients.php" class="btn-small">Ingredients</a>
                <a href="show_recipes.php" class="btn-small">Recipes</a>
            </div>
        </section>
    <?php endif; ?>

</main>

</body>
</html>