<?php
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = "";     // Change this to your database password
$dbname = "pharmacy_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
