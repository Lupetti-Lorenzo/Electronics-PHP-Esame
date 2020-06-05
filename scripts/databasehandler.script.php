<?php 

$serverName = 'localhost';
$dbUsername = 'Cliente';
$dbPassword = 'Cliente';
$dbName = 'Electronics';

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);
if (!$conn) {
    die('Connection failed: '.mysqli_connect_error());
}
