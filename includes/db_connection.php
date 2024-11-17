<?php
// Database connection settings
$host = 'localhost'; // Change if your database is hosted elsewhere
$db_name = 'LibrarySystem'; // Name of the database
$username = 'root'; // Your MySQL username
$password = ''; // Your MySQL password

// Create a connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>