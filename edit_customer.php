<?php
require 'DBconnect.php';

// 1. Fetch Existing Data
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM Customers WHERE customer_id = '$id'";
    $result = mysqli_query($conn, $sql);
    $customer = mysqli_fetch_assoc($result);

    if (!$customer) {
        die("Customer not found!");
    }
}

// 2. Handle Form Submission (Update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['customer_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $points = intval($_POST['points']); // Admin can manually fix points if needed

    $updateSql = "UPDATE Customers SET name='$name', phone_number='$phone', email='$email', points=$points WHERE customer_id='$id'";

    if (mysqli_query($conn, $updateSql)) {
        echo "<script>alert('Customer Updated Successfully!'); window.location.href='customer_list.php';</script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Customer</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" /> 
    
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 600px; margin: auto; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input { width: 100%; padding: 10px; margin-top: 5px; }
        button { margin-top: 20px; padding: 10px 20px; background: #f8a7b9; color: white; border: none; cursor: pointer; }
        .cancel { background: #6c757d; text-decoration: none; padding: 10px 20px; color: white; display: inline-block; margin-left: 10px;}
    </style>
</head>
<body>
    <h2>Edit Customer Profile</h2>
    
    <form method="POST">
        <input type="hidden" name="customer_id" value="<?= $customer['customer_id'] ?>">

        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($customer['name']) ?>" required>

        <label>Phone Number:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($customer['phone_number']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>">

        <label>Loyalty Points:</label>
        <input type="number" name="points" value="<?= $customer['points'] ?>">

        <button type="submit">Save Changes</button>
        <a href="customer_list.php" class="cancel">Cancel</a>
    </form>
</body>
</html>