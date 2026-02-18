<?php
require_once("auth.php");
require_once("dbconnect.php");
?>

// Selected month (YYYY-MM)
$selected_month = $_GET['month'] ?? date('Y-m');

// Generate month dropdown (from Dec 2025 to current month)
$months = [];
$start = new DateTime('2025-12-01');
$end = new DateTime(date('Y-m-01'));

while ($start <= $end) {
    $months[] = $start->format('Y-m');
    $start->modify('+1 month');
}

// ---------- TOP PRODUCED PRODUCT ----------
$top_sql = "
SELECT p.name, SUM(b.initial_quantity) AS total_produced
FROM product_batches b
JOIN Products p ON b.product_id = p.product_id
WHERE DATE_FORMAT(b.made_timestamp, '%Y-%m') = '$selected_month'
GROUP BY b.product_id
ORDER BY total_produced DESC
LIMIT 1
";
$top_result = mysqli_query($conn, $top_sql);
$top_product = mysqli_fetch_assoc($top_result);

// ---------- PRODUCT-WISE SUMMARY ----------
$product_sql = "
SELECT p.name, SUM(b.initial_quantity) AS total_produced
FROM product_batches b
JOIN Products p ON b.product_id = p.product_id
WHERE DATE_FORMAT(b.made_timestamp, '%Y-%m') = '$selected_month'
GROUP BY b.product_id
ORDER BY total_produced DESC
";
$product_result = mysqli_query($conn, $product_sql);

// ---------- DAILY PRODUCTION ----------
$daily_sql = "
SELECT DATE(b.made_timestamp) AS production_date,
       SUM(b.initial_quantity) AS total_quantity
FROM product_batches b
WHERE DATE_FORMAT(b.made_timestamp, '%Y-%m') = '$selected_month'
GROUP BY DATE(b.made_timestamp)
ORDER BY production_date ASC
";
$daily_result = mysqli_query($conn, $daily_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Production Summary - Bekari</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* PDF Button Styling */
        .print-btn {
            background: #f8a7b9;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .print-btn:hover {
            background: #e66a92;
            color: white;
        }

        /* Top Bar Layout */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
        }

        .highlight-box {
            background: #fff5f7;
            padding: 15px;
            border-left: 5px solid #f8a7b9;
            margin-bottom: 25px;
            border-radius: 4px;
            font-size: 1.1em;
        }

        /* Hide UI elements during print */
        @media print {
            header, .print-btn, form {
                display: none !important;
            }
            body {
                background: white;
            }
            .list_box {
                box-shadow: none;
                border: none;
            }
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
            <li><a href="show_batches.php">Batches</a></li>
            <li><a href="production_summary.php" class="active">Production Summary</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="products_list">
        <div class="list_box">

            <div class="top-bar">
                <h1>Production Summary</h1>
                
                <div style="display: flex; align-items: center; gap: 10px;">
                    <a href="#" onclick="window.print()" class="print-btn">Export to PDF</a>
                    
                    <form method="GET" style="margin: 0;">
                        <select name="month" onchange="this.form.submit()" style="padding: 7px; border-radius: 5px;">
                            <?php foreach ($months as $m): ?>
                                <option value="<?= $m ?>" <?= $m == $selected_month ? 'selected' : '' ?>>
                                    <?= date('F Y', strtotime($m.'-01')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>

            <?php if ($top_product): ?>
                <div class="highlight-box">
                    ⭐ <strong>Top Produced Product:</strong>
                    <?= htmlspecialchars($top_product['name']) ?>
                    (<?= number_format($top_product['total_produced']) ?> units)
                </div>
            <?php endif; ?>

            <h2>Product-wise Production</h2>
            <table class="data_table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="text-align: right;">Total Produced</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($product_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($product_result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td style="text-align: right;"><?= number_format($row['total_produced']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="2" style="text-align:center;">No production data for this month.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <h2 style="margin-top:40px;">Daily Production Breakdown</h2>
            <table class="data_table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th style="text-align: right;">Total Units Produced</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($daily_result) > 0): ?>
                    <?php while ($d = mysqli_fetch_assoc($daily_result)): ?>
                        <tr>
                            <td><?= date('d M, Y', strtotime($d['production_date'])) ?></td>
                            <td style="text-align: right;"><?= number_format($d['total_quantity']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="2" style="text-align:center;">No daily data available.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

        </div>
    </section>
</main>

</body>
</html>