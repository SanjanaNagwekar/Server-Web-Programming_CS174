<?php

session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access denied. Please login.');
}

if (!isset($_GET['thread_id']) || !is_numeric($_GET['thread_id'])) {
    header("HTTP/1.1 400 Bad Request");
    exit('Error, please try again!');
}

$thread_id = intval($_GET['thread_id']);
$user_id = $_SESSION['user_id'];

$stmt = $dbConnection->prepare("SELECT file_content FROM threads WHERE thread_id = ? AND user_id = ?");
$stmt->bind_param('ii', $thread_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($thread = $result->fetch_assoc()) {
    echo htmlspecialchars($thread['file_content']);
} else {
    header("HTTP/1.1 404 Not Found");
    echo 'Error, please try again!';
}

$stmt->close();
?>
