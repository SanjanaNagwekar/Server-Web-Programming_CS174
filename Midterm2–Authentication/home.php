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
    exit;
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $threads = fetchUserThreads($user_id, $dbConnection);
} else {
    header("Location: login.php");
    exit;
}

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homepage</title>
</head>
<body>';

echo '<h1>Welcome, ' . htmlspecialchars($_SESSION['userDisplayName']) . '!</h1>';
echo '<form action="upload.php" method="post" enctype="multipart/form-data">
        Thread Name: <input type="text" name="thread_name" required>
        Upload File: <input type="file" name="file_content" accept=".txt" required>
        <input type="submit" value="Upload">
    </form>
    <form method="post" action="">
        <button type="submit" name="logout" value="logout">Log out</button>
    </form>
    <hr>';

echo '<button id="toggleButton" onclick="toggleAllContent()">Expand/Collapse all</button>';

foreach ($threads as $thread) {
    echo '<div>';
    echo '<h2>' . htmlspecialchars(stripslashes($thread['thread_name'])) . '</h2>'; // Use stripslashes() to clean the string
    $processedContent = str_replace("\\n", "\n", $thread['file_content']);
    $fullContent = nl2br(htmlspecialchars($processedContent));
    echo '<p id="content-' . $thread['thread_id'] . '" class="content" data-full="' . htmlspecialchars($fullContent) . '">' . htmlspecialchars(substr($processedContent, 0, 20)) . '...</p>';
    echo '</div>';
}

echo '
<script>
var allExpanded = false;

function toggleAllContent() {
    var contents = document.getElementsByClassName("content");
    Array.from(contents).forEach(function(content) {
        var fullText = content.getAttribute("data-full");
        if (!allExpanded) {
            content.innerHTML = fullText;
        } else {
            content.innerHTML = fullText.substr(0, 20) + "...";
        }
    });
    allExpanded = !allExpanded;
    document.getElementById("toggleButton").innerText = allExpanded ? "Collapse all" : "Expand all";
}
</script>

</body>
</html>';
?>
