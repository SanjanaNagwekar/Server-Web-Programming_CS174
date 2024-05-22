<?php

$dbHost = "localhost";
$dbUser = "user3";
$dbPassword = "pass123";
$dbName = "UniversityDB";
$dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($dbConnection->connect_error) {
    die("Connection failed: " . $dbConnection->connect_error);
}
?>
