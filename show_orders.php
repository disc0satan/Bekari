<?php
require_once("auth.php");
require 'DBconnect.php';
include('header.php');

$role       = $_SESSION['role'];
$locationId = $_SESSION['location_id'];
// --- FETCH ALL ORDERS (Basic Functionality) ---

// --- SEARCH LOGIC ---
$search_term = "";
$where_clause = "";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    // Search by Phone Number OR Customer Name
    $where_clause = "WHERE c.phone_number LIKE '%$search%' OR c.name LIKE '%$search%'";
    $search_term = $_GET['search']; // To keep the input filled after searching
}
$sql = "SELECT 
            o.order_id, 
            o.order_time, 
            o.order_status, 
            c.name AS customer_name,
            c.phone_number,
            (SELECT SUM(quantity_sold * unit_price) 
             FROM Order_items 
             WHERE order_id = o.order_id) AS total_amount
        FROM Orders o
        JOIN Customers c ON o.customer_id = c.customer_id";

// --- BRANCH USERS: FILTER BY LOCATION ---
if ($role !== 'Admin') {
    $sql .= " WHERE o.location_id = '$locationId'";
}

$result = mysqli_query($conn, $sql);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order History - Bekari</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" /> 
</head>
<body>

    <main>
    <div class="list_box">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">Order History</h2>
            <a href="create_order.php" class="btn add-new">+ Create New Order</a>
        </div>

        <?php
        // Show branch name if user is not Admin
        if ($role !== 'Admin') {
            // Get branch name
            $branchName = '';
            $locResult = mysqli_query($conn, "SELECT name FROM location WHERE location_id = '$locationId' LIMIT 1");
            if ($locResult && mysqli_num_rows($locResult) === 1) {
                $branchName = mysqli_fetch_assoc($locResult)['name'];
            }
            if ($branchName !== '') {
                echo "<h3 style='margin-bottom:15px; color:#f8a7b9;'>Orders of {$branchName} Branch</h3>";
            }
        }
        ?>

        <div class="list_box">

            <table class="data_table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date & Time</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($orders) > 0): ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong>#<?= $order['order_id'] ?></strong></td>
                            
                            <td><?= date("M d, Y h:i A", strtotime($order['order_time'])) ?></td>
                            
                            <td>
                                <?= htmlspecialchars($order['customer_name']) ?> 
                                <span style="font-size: 0.85em; color: #888; display: block;">
                                    <?= $order['phone_number'] ?>
                                </span>
                            </td>
                            
                            <td>
                                <span style="padding: 5px 10px; border-radius: 15px; background-color: #d4edda; color: #155724; font-size: 0.9em; font-weight: bold;">
                                    <?= $order['order_status'] ?>
                                </span>
                            </td>
                            
                            <td>৳<?= number_format((float)$order['total_amount'], 2) ?></td>
                            
                            <td class="action-links">
                                <a href="order_details.php?id=<?= $order['order_id'] ?>" class="btn update">View Details</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #777;">
                                No orders found. <a href="create_order.php">Start selling!</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </main>

</body>
</html>