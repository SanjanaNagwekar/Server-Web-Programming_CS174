<?php
$dbHost = "localhost";
$dbUser = "user2";
$dbPassword = "pass123";
$dbName = "midterm2DB";
$dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($dbConnection->connect_error) {
    die("Error: Please try again later.");
}
?>
