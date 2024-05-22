<?php

$dbHost = "localhost";
$dbUser = "user4";
$dbPassword = "pass123";
$dbName = "QuestionsDB";

$dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($dbConnection->connect_error) {
    die("Error: Please try again later.");
}
?>
