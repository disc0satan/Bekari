<?php
require_once("auth.php");
// This ensures ONLY these two roles can even load this page
require_role(['Admin','Branch Manager', 'Branch Worker']); 
require 'DBconnect.php';

// 1. Validation: Did we get an ID?
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No Order ID provided.");
}

$order_id = intval($_GET['id']);

// 2. FETCH ORDER HEADER (Customer & Date)
$sqlOrder = "SELECT o.*, c.name AS customer_name, c.phone_number, c.email
             FROM Orders o
             JOIN Customers c ON o.customer_id = c.customer_id
             WHERE o.order_id = $order_id";
$resOrder = mysqli_query($conn, $sqlOrder);
$order = mysqli_fetch_assoc($resOrder);

if (!$order) {
    die("Error: Order not found!");
}

// 3. FETCH ORDER ITEMS (The Products)
// We join Order_items -> Product_batches -> Products to get the real name
$sqlItems = "SELECT oi.*, p.name AS product_name 
             FROM Order_items oi
             JOIN Product_batches pb ON oi.batch_id = pb.batch_id
             JOIN Products p ON pb.product_id = p.product_id
             WHERE oi.order_id = $order_id";
$resItems = mysqli_query($conn, $sqlItems);
$items = mysqli_fetch_all($resItems, MYSQLI_ASSOC);

// Calculate Grand Total
$grand_total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #<?= $order_id ?> Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Invoice Styling */
        .invoice-box {
            background: white;
            max-width: 800px;
            margin: 40px auto;
            padding: 40px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            color: #555;
        }
        .invoice-header { display: flex; justify-content: space-between; margin-bottom: 40px; }
        .invoice-title { font-size: 2em; color: #333; }
        .invoice-details { text-align: right; }
        
        /* Table Styling specifically for invoice */
        .invoice-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .invoice-table th { background: #f8f9fa; border-bottom: 2px solid #ddd; padding: 10px; text-align: left; }
        .invoice-table td { border-bottom: 1px solid #eee; padding: 10px; }
        .total-row td { border-top: 2px solid #333; font-weight: bold; font-size: 1.2em; padding-top: 15px; }
        
        /* Print Button - Hides itself when printing */
        @media print {
            .no-print { display: none; }
            .invoice-box { border: none; box-shadow: none; }
            body { background: white; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <header>
            <nav>
                <div class="nav_logo"><h1><a href="index.php">Bekari</a></h1></div>
                <ul class="nav_link">
                    <li><a href="show_orders.php">Back to Orders</a></li>
                </ul>
            </nav>
        </header>
    </div>

    <div class="invoice-box">
        
        <div class="invoice-header">
            <div>
                <h1 class="invoice-title">Invoice</h1>
                <strong>Order #<?= $order['order_id'] ?></strong><br>
                Status: <span style="color: green; font-weight:bold;"><?= $order['order_status'] ?></span>
            </div>
            <div class="invoice-details">
                <strong>Bill To:</strong><br>
                <?= htmlspecialchars($order['customer_name']) ?><br>
                <?= htmlspecialchars($order['phone_number']) ?><br>
                <?= htmlspecialchars($order['email']) ?><br>
                <br>
                <strong>Date:</strong> <?= date("F d, Y", strtotime($order['order_time'])) ?><br>
                <strong>Time:</strong> <?= date("h:i A", strtotime($order['order_time'])) ?>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Unit Cost</th>
                    <th>Quantity</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): 
                    $line_total = $item['quantity_sold'] * $item['unit_price'];
                    $grand_total += $line_total;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td>৳<?= number_format($item['unit_price'], 2) ?></td>
                    <td><?= $item['quantity_sold'] ?></td>
                    <td style="text-align: right;">৳<?= number_format($line_total, 2) ?></td>
                </tr>
                <?php endforeach; ?>
                
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Grand Total:</td>
                    <td style="text-align: right;">৳<?= number_format($grand_total, 2) ?></td>
                </tr>
            </tbody>
        </table>
        
        <div class="no-print" style="margin-top: 40px; text-align: center;">
            <button onclick="window.print()" class="btn" style="background: #333; color: white;">🖨️ Print Invoice</button>
            <a href="show_orders.php" class="btn" style="background: #ddd; color: #333;">Close</a>
        </div>

    </div>

</body>
</html>