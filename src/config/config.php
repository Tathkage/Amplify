<!--
File Creator: Tathluach Chol

File Description:
    This file defines the credential for the database connection that will be used in the controller files.
    The file also shows how to create the connection, test if it connected properly, and close the connection.

All Coding Sections: Tathluach Chol
-->

<?php
// Database credentials
const DB_HOST = 'localhost';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
const DB_NAME = 'amplify';

// Create connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);


// Test connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Close the connection
$conn->close();
?>