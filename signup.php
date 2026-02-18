<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set Password - Bekari</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="box">
    <h1>Set Your Password</h1>
    <p>First-time users must set a password</p>

    <form action="signup_process.php" method="POST">
        <input type="text" name="identifier" placeholder="Email or Employee ID" required><br><br>
        <input type="password" name="password" placeholder="New Password" required><br><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>

        <button type="submit">Set Password</button>
    </form>

    <br>
    <a href="home.php">← Back to Login</a>
</div>

</body>
</html>
