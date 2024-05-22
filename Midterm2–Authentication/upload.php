<?php

session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_content']) && isset($_POST['thread_name'])) {
    $threadName = sanitizeInput($_POST['thread_name']);
    $file = $_FILES['file_content'];

    if ($file['type'] !== 'text/plain') {
        echo "Only TXT files are allowed!";
        exit;
    }

    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileContent = file_get_contents($file['tmp_name']);
        $sanitizedContent = sanitizeInput($fileContent);
        $result = handleFileUpload($_SESSION['user_id'], $threadName, $sanitizedContent, $dbConnection);

        if ($result) {
            echo "File uploaded successfully.";
            header("Location: home.php");
            exit();
        } else {
            echo "Error, please try again!";
        }
    } else {
        echo "Error uploading file. Code: " . $file['error'];
    }
} else {
    echo "Error, please try again!";
}
?>
