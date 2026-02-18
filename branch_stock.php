<?php
require_once("auth.php");
require_once("dbconnect.php");
include('header.php');

// Selected branch
$selected_branch = $_GET['branch_id'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Branch Stock Overview - Bekari</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>


<main>
<section class="products_list">
<div class="list_box">

    <!-- Header + Filter -->
    <div class="top-bar">
        <h1>Branch Stock Overview</h1>

        <form method="GET" style="display:flex; gap:10px;">
            <select name="branch_id" required>
                <option value="">All Branches</option>
                <?php
                $branch_sql = "SELECT location_id, name FROM Location WHERE type='branch'";
                $branch_res = mysqli_query($conn, $branch_sql);
                while($b = mysqli_fetch_assoc($branch_res)){
                    $selected = ($selected_branch == $b['location_id']) ? "selected" : "";
                    echo "<option value='{$b['location_id']}' $selected>{$b['name']}</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn submit-btn">Filter</button>
        </form>
    </div>

    <!-- Stock Table -->
    <table class="data_table">
        <thead>
            <tr>
                <th>Branch</th>
                <th>Product</th>
                <th>Total Quantity</th>
            </tr>
        </thead>
        <tbody>

<?php
$where = "";
if (!empty($selected_branch)) {
    $where = "WHERE l.location_id = '$selected_branch'";
}

$sql = "
SELECT 
    l.name AS branch_name,
    p.name AS product_name,
    SUM(bt.quantity) AS total_quantity
FROM batch_transfers bt
JOIN product_batches b ON bt.batch_id = b.batch_id
JOIN Products p ON b.product_id = p.product_id
JOIN Location l ON bt.destination = l.location_id
$where
GROUP BY l.location_id, p.product_id
ORDER BY l.name, p.name
";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0):
    while($row = mysqli_fetch_assoc($result)):
?>
        <tr>
            <td><?= htmlspecialchars($row['branch_name']); ?></td>
            <td><?= htmlspecialchars($row['product_name']); ?></td>
            <td><?= $row['total_quantity']; ?></td>
        </tr>
<?php
    endwhile;
else:
?>
        <tr>
            <td colspan="3" style="text-align:center;">No stock data found.</td>
        </tr>
<?php endif; ?>

        </tbody>
    </table>

</div>
</section>
</main>

</body>
</html>
