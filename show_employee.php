<?php
require_once("auth.php"); // Ensure session is active
require_once("DBconnect.php");
include('header.php');
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("dbconnect.php");

$search = '';

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $sql = "SELECT Employee.*, Location.name AS location_name
            FROM Employee
            LEFT JOIN Location 
            ON Employee.location_id = Location.location_id
            WHERE Employee.first_name LIKE '%$search%'
               OR Employee.last_name LIKE '%$search%'
               OR Employee.role LIKE '%$search%'
               OR Location.name LIKE '%$search%'";
} else {
    $sql = "SELECT Employee.*, Location.name AS location_name
            FROM Employee
            LEFT JOIN Location 
            ON Employee.location_id = Location.location_id";
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
    <title>Employees - Bekari</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css">
    <style>
        .data_table th, .data_table td {
            padding: 12px 15px;
            text-align: left;
        }
        .action-btn {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            margin-right: 5px;
            color: white;
        }
        
        .delete {
            background-color: #ff4d4d;
        }
        .delete:hover {
            background-color: #cc0000;
        }
        .search-form {
            margin-left: auto;
            display: flex;
            align-items: center;
        }
        .search-form input[type="text"] {
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 0.9rem;
        }
        .search-form button {
            padding: 5px 10px;
            margin-left: 5px;
            border: none;
            background-color: #f8a7b9;
            color: white;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-form button:hover {
            background-color: #e66a92;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<main>
    <div class="list_box">
        <div class="top-bar">
            <h1>Employees</h1>
            <!-- Search form -->
            <form class="search-form" method="GET" action="show_employee.php">
                <input type="text" name="search" placeholder="Search employees..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="form-actions" style="margin-bottom: 15px;">
            <a href="add_employee.php" class="btn add-new">+ Add Employee</a>
        </div>

        <table class="data_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['employee_id']; ?></td>
                            <td><?= $row['first_name']; ?></td>
                            <td><?= $row['last_name']; ?></td>
                            <td><?= $row['email']; ?></td>
                            <td><?= $row['role']; ?></td>
                            <td><?= $row['location_name']; ?></td>
                            <td>
                                <?php if ($_SESSION['role'] === 'Admin'): ?>
                                 <a href="edit_employee.php?id=<?= $row['employee_id']; ?>" class="action-btn update">Edit</a>
                                 <a href="delete_employee.php?id=<?= $row['employee_id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
                                 <?php else: ?>
                                    <span style="color:#999; font-style: italic;">View only</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">No employees found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
