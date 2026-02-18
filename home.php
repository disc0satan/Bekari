<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Bekari</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Center everything on the page */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* White card styling */
        .box {
            background: #fff;      
            padding: 40px 30px;    
            border-radius: 12px;   
            box-shadow: 0 6px 15px rgba(0,0,0,0.1); 
            text-align: center;    
        }

        /* Input fields */
        .box input[type="text"],
        .box input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        /* Submit button */
        .box button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            background-color: #e91e63; /* pink */
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .box button:hover {
            background-color: #ff4081; /* lighter pink on hover */
        }

        /* Links */
        .box a {
            color: #e91e63; /* pink */
            text-decoration: none;
            font-weight: bold;
        }

        .box a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="box">
    <h1>Welcome to Bekari</h1>
    <p>Please login to continue</p>

    <!-- LOGIN FORM -->
    <form action="login_process.php" method="POST">
        <input type="text" name="identifier" placeholder="Email or Employee ID" required><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>

    <br>

    <!-- LINKS -->
    <a href="signup.php">Sign Up (First Time User)</a><br><br>
    <a href="forgot_password.php">Forgot Password?</a>
</div>

</body>
</html>
