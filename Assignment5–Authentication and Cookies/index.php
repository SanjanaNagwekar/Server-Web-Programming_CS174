<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config.php';
require_once 'functions.php';

if (isset($_GET['logout'])) {
    logOutUser();
    header("Location: login.php");
    exit();
}

$userLoggedIn = isUserLoggedIn();
$greetingMessage = "Hello!";

if (!$userLoggedIn) {
    header("Location: login.php");
    exit();
}

$login_error = $signup_error = $post_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signup'])) {
        $realname = $_POST['realname'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (signUpUser($username, $password, $realname, $dbConnection)) {
            setRealNameCookie($realname);
            header("Location: index.php");
            exit();
        } else {
            $signup_error = "Username already exists or another sign-up error occurred.";
        }
    } elseif (isset($_POST['login'])) {
        $username = sanitizeInput($_POST['username'], $dbConnection);
        $password = sanitizeInput($_POST['password'], $dbConnection);

        $loginSuccessful = logInUser($username, $password, $dbConnection);

        if ($loginSuccessful) {
            setRealNameCookie($_SESSION['userDisplayName']);
            header("Location: index.php");
            exit();
        } else {
            $login_error = "Incorrect login details provided.";
        }
    } if (isset($_POST['post'])) {
        $title = sanitizeInput($_POST['title'], $dbConnection);
        $content = sanitizeInput($_POST['content'], $dbConnection);

        $postAddedSuccessfully = addPost($title, $content, $_SESSION['userId'], $dbConnection);

        if ($postAddedSuccessfully) {
            header("Location: index.php");
            exit();
        } else {
            $post_error = "There was an error posting your message.";
        }
    }
}

$postsOutput = '';
if ($userLoggedIn) {
    $posts = getUserPosts($_SESSION['userId'], $dbConnection);
    if ($posts) {
        while ($post = $posts->fetch_assoc()) {
            $postsOutput .= '<h2>' . sanitizeForHTML($post['title']) . '</h2>';
            $postsOutput .= '<p>' . nl2br(sanitizeForHTML($post['content'])) . '</p>';
        }
    } else {
        $postsOutput = '<p>No posts to display or there was an error retrieving posts.</p>';
    }
}

if ($userLoggedIn) {
    $realName = isset($_COOKIE['real_name']) ? $_COOKIE['real_name'] : (isset($_SESSION['userDisplayName']) ? $_SESSION['userDisplayName'] : 'User');
    $greetingMessage = "Hello, " . $realName . "!";
} else {
    $greetingMessage = "Hello!";
}

echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homepage</title>
</head>
<body>
    <h1>$greetingMessage</h1>
    <form method="post" action="index.php">
        <input type="text" name="title" placeholder="Post Title" required>
        <textarea name="content" placeholder="Post Content" required></textarea>
        <button type="submit" name="post">Post</button>
    </form>
    <a href="?logout=true">Log out</a>
HTML;
    printErrorMessage($login_error);
    printErrorMessage($signup_error);
    printErrorMessage($post_error);
    echo $postsOutput;
echo '</body>
</html>';
?>
