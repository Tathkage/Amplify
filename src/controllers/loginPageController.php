<?php
require_once '../src/config/config.php';
class loginPageController
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
    public function query($sql)
    {
        return $this->conn->query($sql);
    }


    public function login()
    {
        $this->connect();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get username and password from POST request
            $username = $_POST['username'];
            $password = $_POST['password'];

            // SQL query to check if username and password combination is valid
            $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
            $result = $this->query($sql);

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


        }

        $this->disconnect();


    }








}