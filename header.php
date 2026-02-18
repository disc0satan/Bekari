<header>
    <nav>
        <div class="nav_logo">
            <h1><a href="index.php">Bekari</a></h1>
        </div>
        
        <ul class="nav_link">
            <li class="dropdown">
                <a href="#" class="dropbtn">Inventory ▾</a>
                <ul class="dropdown-content">
                    <li><a href="show_products.php">Products</a></li>
                    <li><a href="show_ingredients.php">Ingredients</a></li>
                    <li><a href="show_recipes.php">Recipes</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#" class="dropbtn">Batches ▾</a>
                <ul class="dropdown-content">
                    <li><a href="show_batches.php">Product Batches</a></li>
                    <li><a href="show_transfer.php">Batch Transfer</a></li>
                    <li><a href="show_waste.php">Waste Log</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#" class="dropbtn">Sales ▾</a>
                <ul class="dropdown-content">
                    <li><a href="create_order.php">New Order</a></li>
                    <li><a href="show_orders.php">Order History</a></li>
                    <li><a href="show_customers.php">Customers</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#" class="dropbtn">Management ▾</a>
                <ul class="dropdown-content">
                    <li><a href="show_locations.php">Locations</a></li>
                    <li><a href="show_employee.php">Employees</a></li>
                    <li><a href="notifications.php">Notifications</a></li>
                </ul>
            </li>

            <li><a href="logout.php" style="color: #f8a7b9; font-weight: bold;">Logout</a></li>
        </ul>
    </nav>
</header>

<style>
/* Integrating dropdown logic into your style.css logic */
.dropdown { position: relative; }
.dropdown-content {
    display: none;
    position: absolute;
    background-color: rgba(255, 255, 255, 0.95); /* Slightly more solid for readability */
    min-width: 180px;
    box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
    z-index: 1000;
    border-radius: 8px;
    padding: 10px 0;
    list-style: none; /* Removes circles from dropdowns */
}
.dropdown:hover .dropdown-content { display: block; }
.dropdown-content li a {
    padding: 10px 20px !important;
    font-size: 0.9em;
    display: block;
}
.dropdown-content li a:hover {
    background-color: #fff5f7;
    color: #f8a7b9 !important;
}
</style>