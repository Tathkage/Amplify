<?php
// Start a session to store user data
session_start();
require_once '../src/config/config.php';

public function connect()
{
    $this->conn =  mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($this->conn->connect_error) {
        die("Connection failed: " . $this->conn->connect_error);
    }
}

// Get the username, email, and password from the form
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$artist = isset($_POST['artist']) ? 1 : 0;

// Validate the username, email, and password
if (empty($username) || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $_SESSION['error'] = 'Invalid username. Only letters, numbers, and underscores are allowed.';
    header('Location: signup.php');
    exit();
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email address.';
    header('Location: signup.php');
    exit();
}
if (empty($password) || strlen($password) < 8) {
    $_SESSION['error'] = 'Password must be at least 8 characters long.';
    header('Location: signup.php');
    exit();
}

// Check if the password and confirm password fields match
if ($password !== $confirm_password) {
    $_SESSION['error'] = 'Passwords do not match.';
    header('Location: signup.php');
    exit();
}

// Hash the password and store the user's data in a database
$pdo = new PDO('mysql:host=localhost;dbname=song_streaming_website', 'username', 'password');

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO users (username, password, email, is_artist) VALUES (?, ?, ?, ?)');
if (!$stmt->execute([$username, $password_hash, $email, $artist])) {
    $_SESSION['error'] = 'Error inserting user into database.';
    header('Location: signup.php');
    exit();
}

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