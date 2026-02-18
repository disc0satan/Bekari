<?php
require_once("auth.php");
require_role(['Admin']);
require_once("dbconnect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Location - Bekari</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <nav>
        <div class="nav_logo"><h1><a href="index.php">Bekari</a></h1></div>
        <ul class="nav_link">
            <li><a href="show_locations.php" class="active">Locations</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="form_container">
        <h2>Add Location</h2>

        <form method="POST" action="insert_location.php" class="data_form">

            <div class="form-group">
                <label>Location Name</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Type</label>
                <select name="type" required>
                    <option value="">-- Select Type --</option>
                    <option value="hub">Hub</option>
                    <option value="branch">Branch</option>
                </select>
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Add Location</button>
                <a href="show_locations.php" class="btn cancel-btn">Cancel</a>
            </div>

        </form>
    </div>
</main>

</body>
</html>
