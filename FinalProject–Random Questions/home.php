<?php
session_start();
require_once 'functions.php';
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logOutUser();
    header("Location: login.php");
    exit();
}

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

$username = htmlspecialchars($_SESSION['username']);
$name = htmlspecialchars($_SESSION['name']);

echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
    <h1>Hello, {$name}!</h1>
    <form id="uploadForm" method="post" enctype="multipart/form-data" onsubmit="return validateFileUpload(this);">
        <label for="file">Upload a .txt file containing questions:</label>
        <input type="file" name="file" id="file" accept=".txt" required>
        <button type="submit">Upload</button>
    </form>
    <div id="uploadResult"></div>
    <form id="getQuestionForm" method="post">
        <button type="submit">Get Random Question</button>
    </form>
    <div id="questionResult"></div>
    <form id="logoutForm" method="post">
        <button type="submit" name="logout">Logout</button>
    </form>
    <script src="validate_functions.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#uploadForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: 'upload.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#uploadResult').html(response);
                    }
                });
            });
            $('#getQuestionForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'get_content.php',
                    type: 'POST',
                    success: function(response) {
                        $('#questionResult').html(response);
                    }
                });
            });
        });
    </script>
</body>
</html>
HTML;
?>
