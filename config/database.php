<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');  // Default in XAMPP
define('DB_PASS', '');      // Empty password in XAMPP
define('DB_NAME', 'php');   // Your actual database name

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}
?>
