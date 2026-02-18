<?php
require_once("auth.php");
// This ensures ONLY these two roles can even load this page
require_role(['Admin','Branch Manager', 'Branch Worker']); 
require 'DBconnect.php';

if (!isset($_GET['id'])) {
    header("Location: show_orders.php");
    exit;
}

$order_id = $_GET['id'];

// Fetch just enough info to confirm (Customer Name & Total)
$sql = "SELECT c.name, o.order_time, 
       (SELECT SUM(quantity_sold * unit_price) FROM Order_items WHERE order_id = o.order_id) as total
       FROM Orders o
       JOIN Customers c ON o.customer_id = c.customer_id
       WHERE o.order_id = $order_id";
$result = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success!</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .success-card {
            background: white;
            max-width: 600px;
            margin: 50px auto;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
            border-top: 5px solid #28a745;
        }
        .icon-check {
            background: #d4edda;
            color: #28a745;
            width: 80px; height: 80px;
            line-height: 80px;
            border-radius: 50%;
            font-size: 40px;
            margin: 0 auto 20px auto;
        }
        .btn-primary { background: #f8a7b9; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-outline { border: 2px solid #f8a7b9; color: #f8a7b9; background: white; }
        .btn-outline:hover { background: #f8a7b9; color: white; }
    </style>
</head>
<body>

    <header>
        <nav>
            <div class="nav_logo"><h1><a href="index.php">Bekari</a></h1></div>
            <ul class="nav_link">
                <li><a href="show_orders.php">Orders</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="success-card">
            <div class="icon-check">✓</div>
            
            <h1 style="color: #28a745; margin-bottom: 10px;">Order Successful!</h1>
            <p style="color: #666; font-size: 1.1em;">
                Order <strong>#<?= $order_id ?></strong> for <strong><?= htmlspecialchars($order['name']) ?></strong> has been saved.
            </p>
            
            <h2 style="margin: 20px 0; color: #333;">
                Total: $<?= number_format($order['total'], 2) ?>
            </h2>

            <div style="margin-top: 30px; display: flex; flex-direction: column; gap: 10px;">
                <a href="order_details.php?id=<?= $order_id ?>" class="btn btn-primary" style="padding: 15px; text-decoration: none; border-radius: 10px; font-weight: bold;">
                    📄 View & Print Invoice
                </a>

                <a href="create_order.php" class="btn btn-outline" style="padding: 15px; text-decoration: none; border-radius: 10px; font-weight: bold;">
                    + Create Another Order
                </a>
                
                <a href="show_orders.php" style="color: #888; text-decoration: none; margin-top: 10px; font-size: 0.9em;">
                    Return to Order List
                </a>
            </div>
        </div>
    </main>

</body>
</html>