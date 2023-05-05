<?php
// Start a session to store user data
session_start();

// Get the username, email, and password from the form
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$artist = isset($_POST['artist']) ? 1 : 0;

// TODO: Validate the username, email, and password
// For example, you might check if the email is valid, if the username is unique, and if the password meets certain criteria

// Check if the password and confirm password fields match
if ($password !== $confirm_password) {
    $_SESSION['error'] = 'Passwords do not match.';
    header('Location: signup.php');
    exit();
}

// TODO: Hash the password and store the user's data in a database
// For example, you might use the PHP password_hash() function to securely hash the password
$pdo = new PDO('mysql:host=localhost;dbname=song_streaming_website', 'username', 'password');

// insert the user into the database
$stmt = $pdo->prepare('INSERT INTO users (username, password, email, is_artist) VALUES (?, ?, ?, ?)');
$stmt->execute([$username, $password, $email, $artist]);
// Store the user's data in the session and redirect to the home page
$_SESSION['username'] = $username;
$_SESSION['email'] = $email;
$_SESSION['loggedin'] = true;
header('Location: homePage.php');
exit();


// get the form data
//$username = $_POST['username'];
//$password = $_POST['password'];
//$email = $_POST['email'];
//$artist = isset($_POST['artist']) ? 1 : 0;

// connect to the database
//$pdo = new PDO('mysql:host=localhost;dbname=song_streaming_website', 'username', 'password');

// insert the user into the database
//$stmt = $pdo->prepare('INSERT INTO users (username, password, email, is_artist) VALUES (?, ?, ?, ?)');
//$stmt->execute([$username, $password, $email, $artist]);
?>