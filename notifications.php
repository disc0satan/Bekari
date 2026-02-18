<?php
session_start();
require_once('DBconnect.php');

// Security: user must be logged in
if (!isset($_SESSION['logged_in'])) {
    header("Location: home.php");
    exit();
}

$role = $_SESSION['role'];
$branch_id = $_SESSION['location_id'];

// Handle "mark as read"
if (isset($_GET['read_id'])) {
    $read_id = (int) $_GET['read_id'];

    mysqli_query(
        $conn,
        "DELETE FROM notifications WHERE notification_id = $read_id"
    );

    header("Location: notifications.php");
    exit();
}

// Fetch notifications for this user
$sql = "
    SELECT *
    FROM notifications
    WHERE role = '$role'
    AND (branch_id IS NULL OR branch_id = $branch_id)
    ORDER BY created_at DESC
";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications - Bekari</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <nav>
        <div class="nav_logo">
            <h1><a href="index.php">Bekari</a></h1>
        </div>
        <ul class="nav_link">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="notifications.php" class="active">Notifications</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="list_box">
        <h1 style="text-align:center;">Notifications</h1>

        <?php if (mysqli_num_rows($result) === 0): ?>
            <p style="text-align:center; color:#777;">
                No new notifications 🎉
            </p>
        <?php else: ?>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div style="
                    background:#fff;
                    padding:15px 20px;
                    margin:15px auto;
                    max-width:800px;
                    border-radius:8px;
                    box-shadow:0 2px 6px rgba(0,0,0,0.1);
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                ">
                    <span>
                        <?php echo htmlspecialchars($row['message']); ?>
                    </span>

                    <a href="notifications.php?read_id=<?php echo $row['notification_id']; ?>"
                       class="action-btn update">
                        ✅
                    </a>
                </div>
            <?php endwhile; ?>

        <?php endif; ?>
    </section>
</main>

</body>
</html>
