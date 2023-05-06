<?php
require_once '../src/config/config.php';
class signupPageController
{
    private $conn;

    // Connect to database
    public function connect()
    {
        $this->conn =  mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // disconnect from database
    public function disconnect()
    {
        if (mysqli_connect_errno()) {
            $this->conn->close();
        }
    }

    // Validate user input
    public function validateInput($firstName, $lastName, $username, $password, $email)
    {
        // Check if all fields are filled in
        if (empty($firstName) || empty($lastName) || empty($username) || empty($password) || empty($email)) {
            $_SESSION['error'] = 'All fields are required.';
            header('Location: signupPage.php');
            return;
        }

        // Validate email address
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Invalid email address.';
            header('Location: signupPage.php');
            return;
        }

        // Check if username or email already exists
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = 'Username or email already exists.';
            header('Location: signupPage.php');
            return;
        }

        $this->insertUser($firstName, $lastName, $username, $password, $email);

        $_SESSION['success'] = 'User created successfully.';
        header('Location: homePage.php');
        exit();
    }

    // Insert user data into database
    public function insertUser($firstName, $lastName, $username, $password, $email)
    {
        $this->connect();

            //    get information from song form
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_POST['email'];
        $isArtist = isset($_POST['isArtist']) ? 1 : 0;



            // query for inputting new song
        $userInput = mysqli_prepare($this->conn, 'INSERT INTO users ($first_name, $last_name, $username, $password, $email) VALUES (?,?,?,?,?)');
        mysqli_stmt_bind_param($userInput, 'sssss', $firstName, $lastName, $username, $password, $email);
        mysqli_stmt_execute($userInput);
        mysqli_stmt_close($userInput);

        $this->disconnect();






        /*$pdo = new PDO('mysql:host=localhost;dbname=amplify', 'username', 'password');

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('INSERT INTO users ($first_name, $last_name, $username, $password, $email) VALUES (?, ?, ?,?,?)');
        if (!$stmt->execute([$username, $password_hash, $firstName, $lastName, $email])) {
            $_SESSION['error'] = 'Error inserting user into database.';
            header('Location: signupPage.php');
            exit();
        }
*/
    //    $userInput = mysqli_prepare($this->conn, 'INSERT INTO users (first_name, last_name, username, password, email) VALUES (?, ?, ?, ?, ?)');
    //    mysqli_stmt_bind_param($userInput, 'sssss', $firstName, $lastName, $username, $password, $email);
    //    mysqli_stmt_execute($userInput);
     //   mysqli_stmt_close($userInput);

    //    $stmt = $this->conn->prepare('INSERT INTO users (first_name, last_name, username, password, email) VALUES (?, ?, ?, ?, ?)');
    //    $stmt->bind_param('sssss', $firstName, $lastName, $username, $password, $email);
    //    echo $stmt->debugDumpParams(); // Output the SQL query to the browser console or log file
     //   $stmt->execute();

   /*     if ($stmt->affected_rows > 0) {
            $_SESSION['success'] = 'User created successfully.';
            header('Location: homePage.php');
            exit();
        } else {
            $_SESSION['error'] = 'Failed to create user.';
            header('Location: signupPage.php');
            exit();
        }
  */
    }

    // Handle form submission
    public function handleForm()
    {
        $this->connect();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['newUser'])) {
                $this->insertUser();
            }


     //       $this->validateInput($firstName, $lastName, $username, $password, $email);

      //            $_SESSION['username'] = $username;
    //              $_SESSION['email'] = $email;
        //          $_SESSION['loggedin'] = true;
          //        header('Location: homePage.php');
            //     exit();

        }


        $this->disconnect();
    }


}






    /*    public function signupp()
        {

            // Get the username, email, and password from the form
            $first = $_POST['first_name'];
            $last = $_POST['last_name'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $artist = isset($_POST['artist']) ? 1 : 0;

    // Validate the username, email, and password
            if (empty($username) || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $_SESSION['error'] = 'Invalid username. Only letters, numbers, and underscores are allowed.';
                header('Location: signupPage.php');
                exit();
            }
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Invalid email address.';
                header('Location: signupPage.php');
                exit();
            }
            if (empty($password) || strlen($password) < 8) {
                $_SESSION['error'] = 'Password must be at least 8 characters long.';
                header('Location: signupPage.php');
                exit();
            }

    // Check if the password and confirm password fields match
            if ($password !== $confirm_password) {
                $_SESSION['error'] = 'Passwords do not match.';
                header('Location: signupPage.php');
                exit();
            }

    // Hash the password and store the user's data in a database
            $pdo = new PDO('mysql:host=localhost;dbname=amplify', 'username', 'password');

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare('INSERT INTO users (username, password, first_name, last_name, email) VALUES (?, ?, ?,?,?)');
            if (!$stmt->execute([$username, $password_hash, $first, $last, $email])) {
                $_SESSION['error'] = 'Error inserting user into database.';
                header('Location: signupPage.php');
                exit();
            }

    // Store the user's data in the session and redirect to the home page
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['loggedin'] = true;
            header('Location: homePage.php');
            exit();











        }
    */

