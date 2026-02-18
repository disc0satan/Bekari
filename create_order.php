<?php
require_once("auth.php");

$employeeLocationId = $_SESSION['location_id'];
require_role(['Admin','Branch Manager', 'Branch Worker']);
require 'DBconnect.php';
// 3. FETCH ONLY THE LOGGED-IN WORKER'S LOCATION
$locResult = mysqli_query($conn, "SELECT * FROM location WHERE location_id = '$employeeLocationId'"); 
$userLocation = mysqli_fetch_assoc($locResult); // Get just this one row

// We can keep this for safety, but $userLocation is what we'll use now
$locationName = $userLocation ? $userLocation['name'] : 'Unknown Location';
// Handle pre-selected customer
$preselected_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : '';

// 1. FETCH CUSTOMERS
$custResult = mysqli_query($conn, "SELECT * FROM Customers ORDER BY name ASC");
$customers = mysqli_fetch_all($custResult, MYSQLI_ASSOC);

// 2. FETCH PRODUCTS
$prodResult = mysqli_query($conn, "SELECT * FROM Products ORDER BY name ASC");
$products = mysqli_fetch_all($prodResult, MYSQLI_ASSOC);

// 3. FETCH LOCATIONS 
$locResult = mysqli_query($conn, "SELECT * FROM location"); 
$locations = mysqli_fetch_all($locResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Order</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" /> 
    
    <style>
        /* (Keep your CSS exactly the same as before) */
        .form_container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(248, 167, 185, 0.2);
            padding: 40px;
            max-width: 900px;
            width: 100%;
        }
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
        .item-row { 
            display: flex; 
            gap: 15px; 
            margin-bottom: 15px; 
            align-items: flex-end; 
            background: #fafafa; 
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #f0f0f0;
            transition: transform 0.2s;
        }
        .item-row:hover { border-color: #f8a7b9; }
        .remove-btn { 
            background: #ffeff1; 
            color: #dc3545; 
            border: 1px solid #ffccd2; 
            width: 42px; height: 42px; 
            border-radius: 10px; 
            cursor: pointer; 
            font-weight: bold; font-size: 1.2rem;
            transition: 0.3s;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .remove-btn:hover { 
            background: #dc3545; color: white; border-color: #dc3545;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
        }
        .btn-add-item {
            background-color: transparent;
            color: #007bff;
            border: 2px dashed #007bff; 
            padding: 12px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            margin-top: 10px;
        }
        .btn-add-item:hover { background-color: #eef7ff; border-style: solid; }
        label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #eee, transparent);
            margin: 30px 0;
        }
        .new-customer-link {
            font-size: 0.85rem;
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
            background: #eef7ff;
            padding: 5px 10px;
            border-radius: 5px;
            transition: 0.2s;
        }
        .new-customer-link:hover { background: #007bff; color: white; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .item-row { animation: fadeIn 0.3s ease-out; }
    </style>
    
    <script>
    const products = <?php echo json_encode($products); ?>;
    let currentLocationId = "";

    function updateLocation() {
        currentLocationId = document.getElementById('location_select').value;
    }

    function addRow() {
        const container = document.getElementById('items-container');
        const index = container.children.length; 
        
        const div = document.createElement('div');
        div.className = 'item-row';
        
        div.innerHTML = `
            <div class="form-group" style="flex: 3;">
                <label>Product</label>
                <select name="product_id[]" onchange="loadBatches(this, ${index})" required class="styled-input">
                    <option value="">-- Select Product --</option>
                    ${products.map(p => `<option value="${p.product_id}" data-base-price="${p.base_price}">${p.name}</option>`).join('')}
                </select>
            </div>
            <div class="form-group" style="flex: 3;">
                <label>Batch (Available Stock)</label>
                <select name="batch_id[]" id="batch_${index}" onchange="updateBatchPrice(this, ${index})" required class="styled-input">
                    <option value="">(Select Product First)</option>
                </select>
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Qty</label>
                <input type="number" name="quantity[]" min="1" value="1" required class="styled-input" style="text-align: center;">
            </div>
            <div class="form-group" style="flex: 1.5;">
                <label>Price</label>
                <input type="number" step="0.01" name="price[]" id="price_${index}" placeholder="0.00" required class="styled-input">
            </div>
            <button type="button" class="remove-btn" onclick="this.parentElement.remove()" title="Remove Item">&times;</button>
        `;
        container.appendChild(div);
    }

    function loadBatches(productSelect, rowIndex) {
        const productId = productSelect.value;
        const batchSelect = document.getElementById('batch_' + rowIndex);
        const priceInput = document.getElementById('price_' + rowIndex);
        
        if(currentLocationId === "") {
            alert("Please select a Store Location first!");
            productSelect.value = ""; 
            return;
        }

        // 1. Set default base price first
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const basePrice = selectedOption.getAttribute('data-base-price');
        priceInput.value = basePrice; 

        batchSelect.innerHTML = '<option value="">Loading...</option>';

        // 2. Fetch Batches
        fetch(`get_batches.php?product_id=${productId}&location_id=${currentLocationId}`)
            .then(res => res.json())
            .then(data => {
                batchSelect.innerHTML = '<option value="">-- Select Batch --</option>';
                
                if(data.length === 0) {
                    batchSelect.innerHTML = '<option value="">Out of Stock</option>';
                } else {
                    data.forEach(b => {
                        let opt = document.createElement('option');
                        opt.value = b.batch_id;
            
                        opt.setAttribute('data-price', b.current_price);
                        
                        // Show text like: "Batch #10 (Qty: 5) - $250.00 (50% OFF)"
                        opt.text = `Batch #${b.batch_id} (Qty: ${b.calculated_stock}) - ${b.status_text}`;
                        
                        batchSelect.add(opt);
                    });
                }
            });
    }

    // KEY CHANGE 3: 
    function updateBatchPrice(batchSelect, rowIndex) {
        const priceInput = document.getElementById('price_' + rowIndex);
        
        // Find the selected option (the specific batch)
        const selectedOption = batchSelect.options[batchSelect.selectedIndex];
        
        const newPrice = selectedOption.getAttribute('data-price');
        
        // Update the input box!
        if (newPrice) {
            priceInput.value = newPrice;
            
            // Optional: Visual feedback (Flash yellow to show price changed)
            priceInput.style.backgroundColor = "#fff3cd";
            setTimeout(() => priceInput.style.backgroundColor = "#fff", 500);
        }
    }
</script>
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
                <li><a href="customer_list.php" style="color: #f8a7b9;">Customers</a></li> 
            </ul>
        </nav>
    </header>

    <main>
        
        <div class="form_container">
            
            <h2 style="margin-bottom: 5px; color: #f8a7b9; font-size: 2rem;">Create New Order</h2>
            <p style="color: #999; margin-bottom: 30px;">Select location, customer, and items.</p>

            <form action="process_order.php" method="POST">
                
                <div class="form-group" style="background: #fff0f3; padding: 20px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #ffccd2;">
                    <label style="color: #d63384; font-weight: bold; font-size: 1rem;">📍 Where is this order happening?</label>
                    <select name="location_id" id="location_select" class="styled-input" required onchange="updateLocation()" style="border-color: #f8a7b9;">
                        <option value="">-- Select Store Location --</option>
                        <?php foreach($locations as $loc): ?>
                            <option value="<?= $loc['location_id'] ?>"><?= $loc['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <label style="margin-bottom: 0;">Customer Name:</label>
                        <a href="customers.php" class="new-customer-link">+ Create New Customer</a>
                    </div>
                    
                    <select name="customer_id" required class="styled-input" style="font-weight: 500;">
                        <option value="">-- Select Existing Customer --</option>
                        <?php foreach ($customers as $c): ?>
                            <option value="<?= $c['customer_id'] ?>" <?= ($c['customer_id'] == $preselected_id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['name']) ?> (<?= $c['phone_number'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="divider"></div>

                <h3 style="color: #555; font-size: 1.2em; margin-bottom: 20px;">Order Items</h3>
                
                <div id="items-container">
                    </div>

                <button type="button" onclick="addRow()" class="btn-add-item">
                    + Add Another Item
                </button>
                
                <div class="form-actions" style="margin-top: 50px; display: flex; gap: 15px;">
                    <button type="submit" class="btn submit-btn" style="flex: 2; padding: 15px; font-size: 1.1rem;">
                        Confirm Order
                    </button>
                    
                    <a href="customers.php" class="btn cancel-btn" style="flex: 1; text-align: center; padding: 15px; font-size: 1.1rem;">
                        Cancel
                    </a>
                </div>
            </form>

        </div>
    </main>

    <script>
        window.onload = function() { addRow(); };
    </script>

</body>
</html>