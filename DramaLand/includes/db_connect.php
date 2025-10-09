<?php
// includes/db_connect.php
require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, dramaland_db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>