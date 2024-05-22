<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isUserLoggedIn()) {
    echo "User is not logged in.";
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $dbConnection->prepare("SELECT question FROM Questions WHERE user_id = ? ORDER BY RAND() LIMIT 1");
if ($stmt === false) {
    error_log('MySQL prepare error: ' . $dbConnection->error);
    echo "An error occurred, please try again later.";
    exit();
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($question);
$stmt->fetch();
$stmt->close();

if ($question === null) {
    echo "No questions entered.";
} else {
    echo "Random Question: " . htmlspecialchars($question);
}
?>
