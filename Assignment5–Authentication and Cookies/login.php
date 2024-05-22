<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config.php';
require_once 'functions.php';

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (logInUser($username, $password, $dbConnection)) {
        header("Location: index.php");
        exit();
    } else {
        $login_error = "The username and password combination is incorrect.";
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
