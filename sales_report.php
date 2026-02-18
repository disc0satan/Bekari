<?php
require_once("auth.php");
require_once("dbconnect.php");

/* ======================
   Month selection logic
====================== */
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$monthStart = $selectedMonth . "-01";
$monthEnd = date("Y-m-t", strtotime($monthStart));

/* ======================
   Overall sales
====================== */
$totalSalesSql = "
SELECT SUM(oi.quantity_sold * oi.unit_price) AS total_sales
FROM order_items oi
JOIN orders o ON oi.order_id = o.order_id
WHERE o.order_status = 'Completed'
AND o.order_time BETWEEN '$monthStart' AND '$monthEnd'
";
$totalSalesRes = mysqli_query($conn, $totalSalesSql);
$totalSales = mysqli_fetch_assoc($totalSalesRes)['total_sales'] ?? 0;

/* ======================
   Top selling product (overall)
====================== */
$topProductSql = "
SELECT p.name, SUM(oi.quantity_sold) AS total_qty
FROM order_items oi
JOIN orders o ON oi.order_id = o.order_id
JOIN product_batches b ON oi.batch_id = b.batch_id
JOIN products p ON b.product_id = p.product_id
WHERE o.order_status = 'Completed'
AND o.order_time BETWEEN '$monthStart' AND '$monthEnd'
GROUP BY p.product_id
ORDER BY total_qty DESC
LIMIT 1
";
$topProductRes = mysqli_query($conn, $topProductSql);
$topProduct = mysqli_fetch_assoc($topProductRes);

/* ======================
   Branch-wise top products
====================== */
$branchTopSql = "
SELECT 
    l.name AS branch_name,
    p.name AS product_name,
    SUM(oi.quantity_sold) AS total_qty
FROM order_items oi
JOIN orders o ON oi.order_id = o.order_id
JOIN product_batches b ON oi.batch_id = b.batch_id
JOIN products p ON b.product_id = p.product_id
JOIN location l ON o.location_id = l.location_id
WHERE o.order_status = 'Completed'
AND o.order_time BETWEEN '$monthStart' AND '$monthEnd'
GROUP BY l.location_id, p.product_id
ORDER BY l.location_id, total_qty DESC
";
$branchTopRes = mysqli_query($conn, $branchTopSql);

/* ======================
   Build branch-wise array
====================== */
$branchData = [];
while ($row = mysqli_fetch_assoc($branchTopRes)) {
    if (!isset($branchData[$row['branch_name']])) {
        $branchData[$row['branch_name']] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sales Report - Bekari</title>
<link rel="stylesheet" href="css/style.css">
<style>
.report-box {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 25px;
}
.report-box h2 {
    margin-bottom: 10px;
}
.print-btn {
    background: #f8a7b9;
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
}
.print-btn:hover {
    background: #e66a92;
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
<li><a href="index.php">Dashboard</a></li>
<li><a href="sales_report.php" class="active">Sales Report</a></li>
</ul>
</nav>
</header>

<main>
<section class="products_list">
<div class="list_box">

<!-- Top bar -->
<div class="top-bar" style="display:flex;justify-content:space-between;align-items:center;">
<h1>Sales Report</h1>
<a href="#" onclick="window.print()" class="print-btn">Export to PDF</a>
</div>

<!-- Month selector -->
<form method="GET" style="margin-bottom:20px;">
<label><strong>Select Month:</strong></label>
<select name="month" onchange="this.form.submit()">
<?php
for ($i = 0; $i < 6; $i++) {
    $m = date("Y-m", strtotime("-$i month"));
    $selected = ($m == $selectedMonth) ? "selected" : "";
    echo "<option value='$m' $selected>" . date("F Y", strtotime($m)) . "</option>";
}
?>
</select>
</form>

<!-- Overall Sales -->
<div class="report-box">
<h2>Total Sales (<?= date("F Y", strtotime($selectedMonth)) ?>)</h2>
<p><strong>৳ <?= number_format($totalSales, 2) ?></strong></p>
</div>

<!-- Top Product -->
<div class="report-box">
<h2>Top Selling Product</h2>
<?php if ($topProduct): ?>
<p><?= htmlspecialchars($topProduct['name']) ?> — <?= $topProduct['total_qty'] ?> units</p>
<?php else: ?>
<p>No sales data</p>
<?php endif; ?>
</div>

<!-- Branch-wise -->
<div class="report-box">
<h2>Branch-wise Top Products</h2>
<table class="data_table">
<thead>
<tr>
<th>Branch</th>
<th>Top Product</th>
<th>Units Sold</th>
</tr>
</thead>
<tbody>
<?php if (!empty($branchData)): ?>
<?php foreach ($branchData as $branch => $data): ?>
<tr>
<td><?= htmlspecialchars($branch) ?></td>
<td><?= htmlspecialchars($data['product_name']) ?></td>
<td><?= $data['total_qty'] ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td colspan="3" style="text-align:center;">No data</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>

</div>
</section>
</main>

</body>
</html>
