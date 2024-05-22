<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isUserLoggedIn()) {
    echo "User is not logged in.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    if ($_FILES['file']['type'] == 'text/plain') {
        if ($_FILES['file']['size'] > 2 * 1024 * 1024) {
            echo "File size exceeds the maximum limit of 2MB.";
            exit();
        }

        $file = fopen($_FILES['file']['tmp_name'], 'r');
        $user_id = $_SESSION['user_id'];
        $newQuestionsCount = 0;

        while ($line = fgets($file)) {
            $question = trim($line);
            if (!empty($question) && !questionExists($dbConnection, $user_id, $question)) {
                addQuestion($dbConnection, $user_id, $question);
                $newQuestionsCount++;
            }
        }

        fclose($file);
        echo "File uploaded and processed successfully! $newQuestionsCount new questions added.";
    } else {
        echo "Only .txt files are allowed!";
    }
} else {
    echo "Invalid request!";
}
?>
