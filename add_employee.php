<?php
require_once("auth.php");
require_role(['Admin']);
require_once("dbconnect.php");
$locations = mysqli_query($conn, "SELECT * FROM Location");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Employee - Bekari</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <nav>
        <div class="nav_logo">
            <h1><a href="index.php">Bekari</a></h1>
        </div>
        <ul class="nav_link">
            <li><a href="show_employee.php" class="active">Employees</a></li>
            <li><a href="show_products.php">Products</a></li>
            <li><a href="show_ingredients.php">Ingredients</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="form_container">
        <h2>Add Employee</h2>

        <form action="insert_employee.php" method="POST">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
</div>


            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" required>
    <option value="">-- Select Role --</option>
    <option value="Admin">Admin</option>
    <option value="Branch Manager">Branch Manager</option>
    <option value="Branch Worker">Branch Worker</option>
    <option value="Hub Manager">Hub Manager</option>
    <option value="Hub Worker">Hub Worker</option>
</select>

            </div>
            <div class="form-group">
    <label>Location:</label>
    <select name="location_id" required>
        <option value="">-- Select Location --</option>
        <?php while ($loc = mysqli_fetch_assoc($locations)) { ?>
            <option value="<?= $loc['location_id']; ?>">
                <?= $loc['name']; ?> (<?= ucfirst($loc['type']); ?>)
            </option>
        <?php } ?>
    </select>
</div>


            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Add Employee</button>
                <a href="show_employees.php" class="btn cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</main>

</body>
</html>

