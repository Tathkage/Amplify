<?php
session_start();

// get the form data
$username = $_POST['username'];
$password = $_POST['password'];

// connect to the database
$pdo = new PDO('mysql:host=localhost;dbname=song_streaming_website', 'username', 'password');

// retrieve the user from the database
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

// check if the username and password are correct
if ($user && password_verify($password, $user['password'])) {
    // set the session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['is_artist'] = $user['is_artist'];

    // redirect the user to the home page
    header('Location: homePage.php');
    exit();
} else {
    // display an error message
    echo '<p>Incorrect username or password.</p>';
}
?>
