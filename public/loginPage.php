<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Connect to database
    $servername = "localhost";
    $username = "your_username";
    $password = "your_password";
    $dbname = "amplify";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get username and password from POST request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to check if username and password combination is valid
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Valid username and password combination
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: homePage.php");
        exit;
    } else {
        // Invalid username and password combination
        $error = "Invalid username or password";
    }

    $conn->close();
}
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













