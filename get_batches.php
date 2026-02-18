<?php

error_reporting(0); 
ini_set('display_errors', 0);
header('Content-Type: application/json');

require 'DBconnect.php';

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$location_id = isset($_GET['location_id']) ? intval($_GET['location_id']) : 1; 

// 2. GET PRODUCT RULES
$prodSql = "SELECT shelf_life, markdown_hours, base_price, discount_pct FROM Products WHERE product_id = $product_id";
$prodResult = mysqli_query($conn, $prodSql);
$product = mysqli_fetch_assoc($prodResult);

// If product not found, return empty list safely
if (!$product) { 
    echo json_encode([]); 
    exit; 
}

// Store rules in variables (handle potential empty values with defaults)
$shelf_life_days = isset($product['shelf_life']) ? intval($product['shelf_life']) : 7;
$markdown_hours  = isset($product['markdown_hours']) ? intval($product['markdown_hours']) : 48;
$base_price      = floatval($product['base_price']);
$discount_val    = isset($product['discount_pct']) ? floatval($product['discount_pct']) : 0;

// 3. FETCH BATCHES
$sql = "SELECT 
            b.batch_id,
            b.made_timestamp,
            (SELECT SUM(quantity) FROM Batch_transfers WHERE batch_id = b.batch_id AND destination = '$location_id') AS total_in,
            (SELECT SUM(quantity_sold) FROM Order_items oi JOIN Orders o ON oi.order_id = o.order_id WHERE 
            oi.batch_id = b.batch_id AND o.location_id = '$location_id') AS total_out
        FROM Product_batches b
        WHERE b.product_id = $product_id
        ORDER BY b.made_timestamp ASC";

$result = mysqli_query($conn, $sql);
$available_batches = [];

$now = time();

while ($row = mysqli_fetch_assoc($result)) {
    // --- TIME CALCULATIONS ---
    $made_time = strtotime($row['made_timestamp']);
    
    // Expiry Time (Made Time + Shelf Life in seconds)
    $expiry_time = $made_time + ($shelf_life_days * 24 * 60 * 60);
    
    // Discount Start Time (Made Time + Markdown Hours in seconds)
    $markdown_start_time = $made_time + ($markdown_hours * 60 * 60);

    // --- LOGIC 1: HIDE EXPIRED BATCHES ---
    if ($now > $expiry_time) {
        continue; 
    }
    $in  = $row['total_in']  ? intval($row['total_in'])  : 0;
    $out = $row['total_out'] ? intval($row['total_out']) : 0;
    $stock = $in - $out;

    if ($stock > 0) {
        // --- LOGIC 2: APPLY DISCOUNT ---
        $current_price = $base_price;
        $status_text = "";
        
        // If we are past the markdown time AND a discount is set
        if ($now >= $markdown_start_time && $discount_val > 0) {
            
            // Calculate Discount Amount
            $discount_amount = $base_price * ($discount_val / 100);
            $current_price = $base_price - $discount_amount;
            
            $status_text = " (" . intval($discount_val) . "% OFF)";
        }

        $row['calculated_stock'] = $stock;
        $row['current_price'] = number_format($current_price, 2);
        $row['status_text'] = $status_text;
        
        $available_batches[] = $row;
    }
}

echo json_encode($available_batches);
?>