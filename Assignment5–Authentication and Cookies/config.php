<?php
$dbHost = "localhost";
$dbUser = "user1";
$dbPassword = "pass123";
$dbName = "assignment5db";
$dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($dbConnection->connect_error) {
    die("Connection failed: " . $dbConnection->connect_error);
}
?>
