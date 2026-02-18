<?php
require_once("auth.php");
// This ensures ONLY these two roles can even load this page
require_role(['Admin','Branch Manager', 'Branch Worker']); 
require 'DBconnect.php';

$search_result = null;

// Handle "Old Customer" Search
// FUNCTIONALITY UNCHANGED
if (isset($_GET['search_phone'])) {
    $phone = $_GET['search_phone'];
    $sql = "SELECT * FROM Customers WHERE phone_number = '$phone'";
    $result = mysqli_query($conn, $sql);
    $search_result = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Management</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css" /> 
    
    <style>
        /* --- MODERN FORM STYLES --- */
        
        body { background-color: #f4f4f9; }

        /* ONE SINGLE CONTAINER FOR BOTH SECTIONS */
        .form_container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(248, 167, 185, 0.2);
            padding: 40px;
            max-width: 800px; /* Width for the combined block */
            width: 100%;
            margin: 0 auto;
        }

        /* Pillowy Inputs */
        .styled-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            color: #555;
            background-color: #fcfcfc;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        .styled-input:focus {
            border-color: #f8a7b9;
            box-shadow: 0 0 0 4px rgba(248, 167, 185, 0.1);
            outline: none;
            background-color: #fff;
        }

        /* Labels */
        label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            margin-bottom: 8px;
            font-weight: 600;
            display: block;
        }
        
        /* Divider Line */
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #eee, transparent);
            margin: 40px 0; /* Spacing between Search and Register */
        }

        /* Result Cards */
        .result-card {
            margin-top: 15px;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #eee;
        }
        .success-box { background-color: #f0fff4; border-color: #c3e6cb; }
        .error-box { background-color: #fff5f5; border-color: #fed7d7; color: #c53030; font-weight: 500;}

        /* Buttons */
        .btn-search {
            background-color: #6c757d; 
            color: white; 
            border: none; 
            padding: 12px 25px; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: 600;
            flex-shrink: 0;
        }
        .btn-search:hover { background-color: #5a6268; }

        .btn-order-link {
            display: inline-block;
            background-color: #f8a7b9;
            color: white;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
            font-size: 0.9rem;
        }
        .btn-order-link:hover { background-color: #ff9fb6; }
        
        .section-title {
            color: #f8a7b9; 
            margin-bottom: 20px; 
            font-weight: 500;
        }

    </style>
</head>
<body>

    <header>
        <nav>
            <div class="nav_logo">
                <h1><a href="index.php">Bekari</a></h1>
            </div>
            <ul class="nav_link">
                <li><a href="show_products.php">Products</a></li>
                <li><a href="show_ingredients.php">Ingredients</a></li>
                <li><a href="show_orders.php">Orders</a></li>
                <li><a href="show_batches.php">Batches</a></li>
                <li><a href="customer_list.php" class="active" style="color: #f8a7b9;">Customers</a></li> 
            </ul>
        </nav>
    </header>

    <main style="padding: 40px 20px;">

        <div class="form_container">

            <h2 class="section-title" style="color: #555;">🔍 Find Existing Customer</h2>
            
            <form method="GET">
                <div class="form-group">
                    <label>Search by Phone Number:</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" name="search_phone" class="styled-input" placeholder="e.g. 01xxxxxxxxx" required>
                        <button type="submit" class="btn-search">Search</button>
                    </div>
                </div>
            </form>

            <?php if (isset($_GET['search_phone'])): ?>
                <?php if ($search_result): ?>
                    <div class="result-card success-box">
                        <h4 style="color: #2f855a; margin: 0 0 5px 0;">✅ Customer Found!</h4>
                        <p style="margin: 0;"><strong><?= htmlspecialchars($search_result['name']) ?></strong> (Points: <?= $search_result['points'] ?>)</p>
                        <a href="create_order.php?customer_id=<?= $search_result['customer_id'] ?>" class="btn-order-link">
                            Create Order &rarr;
                        </a>
                    </div>
                <?php else: ?>
                    <div class="result-card error-box">
                        ❌ No customer found with that phone number.
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="divider"></div>

            <h2 class="section-title">Register New Customer</h2>
            
            <form action="process_customer.php" method="POST">
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Full Name:</label>
                    <input type="text" name="name" class="styled-input" required>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Phone Number:</label>
                    <input type="text" name="phone" class="styled-input" required>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label>Email Address:</label>
                    <input type="email" name="email" class="styled-input">
                </div>

                <button type="submit" class="btn submit-btn" style="width: 100%; padding: 15px; font-size: 1.1rem; background-color: #f8a7b9; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: bold;">
                    Save Customer
                </button>
            </form>

        </div>
        </main>

</body>
</html>