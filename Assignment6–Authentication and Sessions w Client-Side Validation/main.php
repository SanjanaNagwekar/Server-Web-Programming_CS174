<?php

session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
if (!isset($_SESSION['name'])) {
    $displayName = "Guest";
} else {
    $displayName = htmlspecialchars($_SESSION['name']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logOutUser();
    header("Location: login.php");
    exit();
}

$advisor_info = '';
$studentNotFound = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $studentName = sanitizeInput($_POST['studentName']);
    $studentID = sanitizeInput($_POST['studentID']);

    if (validateStudent($studentName, $studentID, $dbConnection)) {
        $advisor_info = getAdvisorInfo($studentID, $dbConnection);
        if (!$advisor_info) {
            $advisor_info = "No advisor found for this ID.";
        }
    } else {
        $studentNotFound = "Invalid student name or ID.";
    }
}

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
</head>
<body>
    <h1>Welcome, ' . $displayName . '</h1>';
    echo '<form method="post" action="main.php">
        <input type="text" name="studentName" placeholder="Enter your Name" required>
        <input type="text" name="studentID" placeholder="Enter your Student ID" required pattern="\d{9}">
        <button type="submit" name="search">Search Advisor</button>
    </form>';

    if (!empty($advisor_info)) {
        echo $advisor_info;
    } elseif (!empty($studentNotFound)) {
        echo '<p style="color: red;">' . $studentNotFound . '</p>';
    }

echo '
<form method="post" action="">
    <button type="submit" name="logout" value="logout">Log out</button>
</form>
<script src="validate_functions.js"></script>
</body>
</html>';
?>
