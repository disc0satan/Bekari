<?php
require_once("dbconnect.php");

if (!isset($_GET['id'])) {
    die("Location ID not provided.");
}

$id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM Location WHERE location_id = $id");
if (mysqli_num_rows($result) == 0) {
    die("Location not found.");
}

$location = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $update = "UPDATE Location SET
               name='$name',
               type='$type',
               address='$address'
               WHERE location_id=$id";

    if (mysqli_query($conn, $update)) {
        header("Location: show_locations.php");
        exit();
    } else {
        die("Update failed: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Location</title>
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
        <h2>Edit Location</h2>

        <form method="POST" class="data_form">

            <div class="form-group">
                <label>Location Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($location['name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Type</label>
                <select name="type" required>
                    <option value="hub" <?= $location['type']=='hub'?'selected':''; ?>>Hub</option>
                    <option value="branch" <?= $location['type']=='branch'?'selected':''; ?>>Branch</option>
                </select>
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" value="<?= htmlspecialchars($location['address']); ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Update Location</button>
                <a href="show_locations.php" class="btn cancel-btn">Cancel</a>
            </div>

        </form>
    </div>
</main>

</body>
</html>

