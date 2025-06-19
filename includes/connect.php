<?php
// includes/connect.php
$host = 'localhost';
$user = 'root';       // change this if you have a different DB user
$pass = '';           // change this if you have a DB password
$dbname = 'estudy_skillzennepal';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>