<?php?>


<!DOCTYPE html>
<html>
<head>
    <title>Login - Amplify</title>
    <link rel="stylesheet" type="text/css" href="css/loginPage.css">
    <link rel="icon" href="./images/amplifyIcon.png" type="image/x-icon">
    <style>
        .center {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
    </style>
</head>
<body>
<div class="center">
    <h1>Login</h1>
    <form method="post" action="login.php">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</div>
</body>
</html>