<?php
// Database connection for XAMPP (default credentials)
$mysqli = new mysqli("localhost", "root", "", "inventoryManager");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>