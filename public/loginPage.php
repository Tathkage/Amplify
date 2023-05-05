<?php
session_start();
require_once '../src/controllers/loginPageController.php';

$controller = new loginPageController();
$controller->login();
?>

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
    <?php if (isset($error)) { ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php } ?>
    <form method="post">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username">
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
        </div>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>













