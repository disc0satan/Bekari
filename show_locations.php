<?php
require_once("auth.php"); // Ensure session is active
// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("dbconnect.php");
include('header.php');

$search = '';

// Simple search logic matching your teammates' current database
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM Location
            WHERE name LIKE '%$search%'
               OR type LIKE '%$search%'
               OR address LIKE '%$search%'
            ORDER BY name ASC";
} else {
    $sql = "SELECT * FROM Location ORDER BY name ASC";
}

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locations - Bekari</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<main>
    <section class="batches_list"> <div class="list_box">
            <h1>Bakery Locations</h1>

            <div class="search-and-add">
                <a href="add_location.php" class="btn add-new" title="Add New Location">+</a>
                
                <form method="GET" action="show_locations.php" class="search-form">
                    <input type="text" name="search" placeholder="Search locations..."
                           value="<?= htmlspecialchars($search); ?>" class="search-input">
                    <button type="submit" class="btn submit-btn">Search</button>
                </form>
            </div>

            <table class="data_table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['location_id']; ?></td>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= ucfirst($row['type']); ?></td>
                            <td><?= htmlspecialchars($row['address']); ?></td>
                            <td class="action-links">
    <?php if ($_SESSION['role'] === 'Admin'): ?>
        <a href="edit_location.php?id=<?= $row['location_id']; ?>" class="action-btn update">Edit</a>
        
        <a href="delete_location.php?id=<?= $row['location_id']; ?>" 
           class="action-btn delete" 
           onclick="return confirm('Permanently delete this location?');">
           Delete
        </a>
    <?php else: ?>
        <span style="color:#999; font-style: italic;">View only</span>
    <?php endif; ?>
</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 20px;">No locations found.</td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>
        </div>
    </section>
</main>

</body>
</html>