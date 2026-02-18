<?php
require_once("auth.php");
// This ensures ONLY these two roles can even load this page
require_role(['Admin','Branch Manager', 'Branch Worker']); 
require 'DBconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cust_id = $_POST['customer_id'];
    $loc_id  = $_POST['location_id'];
    
    // 1. MATCHING YOUR VARIABLES from the HTML above
    $batches = $_POST['batch_id']; 
    $quantities = $_POST['quantity'];
    $prices = $_POST['price'];

    mysqli_begin_transaction($conn);

    try {
        // 2. Create the Main Order Record
        $orderSql = "INSERT INTO Orders (order_status, customer_id, location_id, order_time) 
                     VALUES ('Completed', '$cust_id', '$loc_id', NOW())";
        if (!mysqli_query($conn, $orderSql)) throw new Exception(mysqli_error($conn));
        $order_id = mysqli_insert_id($conn);

        $totalPoints = 0;

        // 3. Loop through items
        for ($i = 0; $i < count($batches); $i++) {
            $b_id = $batches[$i];
            $qty  = $quantities[$i];
            $price = $prices[$i];

            if (empty($b_id) || empty($qty)) continue; 

            // --- A. SIMPLIFIED STOCK CHECK (PHP Logic) ---
            
            // Step 1: Count everything that ARRIVED at this location
            $sqlIn = "SELECT SUM(quantity) as total_in FROM Batch_transfers 
                      WHERE batch_id = '$b_id' AND destination = '$loc_id'";
            $resIn = mysqli_query($conn, $sqlIn);
            $rowIn = mysqli_fetch_assoc($resIn);
            
            // If result is empty (null), use 0
            $total_arrived = $rowIn['total_in'] ? $rowIn['total_in'] : 0;

            // Step 2: Count everything SOLD from this location
            $sqlOut = "SELECT SUM(quantity_sold) as total_sold FROM Order_items oi
                       JOIN Orders o ON oi.order_id = o.order_id
                       WHERE oi.batch_id = '$b_id' AND o.location_id = '$loc_id'";
            $resOut = mysqli_query($conn, $sqlOut);
            $rowOut = mysqli_fetch_assoc($resOut);
            
            // If result is empty (null), use 0
            $total_sold = $rowOut['total_sold'] ? $rowOut['total_sold'] : 0;

            // Step 3: Do the Math
            $available = $total_arrived - $total_sold;

            // Step 4: Validate
            if ($available < $qty) {
                throw new Exception("Stock Error: Batch #$b_id only has $available items remaining.");
            }

            // --- B. Save the Item ---
            $itemSql = "INSERT INTO Order_items (quantity_sold, unit_price, order_id, batch_id) 
                        VALUES ('$qty', '$price', '$order_id', '$b_id')";
            if (!mysqli_query($conn, $itemSql)) throw new Exception("Error saving item: " . mysqli_error($conn));

            $totalPoints += floor($qty * $price * 0.01);
        }

        // 4. Add Points
        if ($totalPoints > 0) {
            $updatePts = "UPDATE Customers SET points = points + $totalPoints WHERE customer_id = $cust_id";
            if (!mysqli_query($conn, $updatePts)) throw new Exception("Error adding points.");
        }

        mysqli_commit($conn);
        header("Location: order_success.php?id=$order_id");
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<h1>Transaction Failed</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<a href='create_order.php'>Go Back</a>";
    }
}
?>