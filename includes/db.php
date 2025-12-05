<?php
// includes/db.php
$servername = "localhost";
$username = "root";       // default XAMPP username
$password = "";           // default password is empty
$dbname = "dramaland_db"; // change this to your actual DB name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  echo "Database connected successfully!";
}
?>
