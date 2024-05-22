<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);

    if (logInUser($username, $password, $dbConnection)) {
        echo 'Login successful';
        echo 'User ID: ' . $_SESSION['user_id'];
        header("Location: home.php");
        exit;
    } else {
        $login_error = "Incorrect username/password combination.";
    }
}

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Hello!<br>Login to your account</h1>';
    printErrorMessage($login_error);

echo '<form method="post" action="login.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p>Don\'t have an account? <a href="signup.php">Sign up here</a>.</p>
</body>
</html>';
?>
