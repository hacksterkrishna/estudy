<?php
// includes/connect.php
$host = 'localhost';
$user = 'hackster';       // change this if you have a different DB user
$pass = 'hackster@#$123';           // change this if you have a DB password
$dbname = 'estudy_skillzennepal';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>