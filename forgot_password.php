<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Bekari</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="box">
    <h1>Forgot Password</h1>
    <p>Enter your Email or Employee ID to reset password</p>

    <form action="reset_password_process.php" method="POST">
        <input type="text" name="identifier" placeholder="Email or Employee ID" required><br><br>
        <input type="password" name="password" placeholder="New Password" required><br><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>

        <button type="submit">Reset Password</button>
    </form>

    <br>
    <a href="home.php">← Back to Login</a>
</div>

</body>
</html>
