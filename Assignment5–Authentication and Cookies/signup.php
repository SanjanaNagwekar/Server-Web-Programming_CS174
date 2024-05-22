<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config.php';
require_once 'functions.php';

$signup_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $realname = $_POST['realname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (signUpUser($username, $password, $realname, $dbConnection)) {
        setRealNameCookie($realname);
        $_SESSION['userDisplayName'] = $realname;
        header("Location: index.php");
        exit();
    } else {
        $signup_error = "Username already exists or another sign-up error occurred.";
    }
}

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
</head>
<body>
    <h1>Create a new account</h1>';
    printErrorMessage($signup_error);
echo '<form method="post" action="signup.php">
        <input type="text" name="realname" placeholder="Your Real Name" required>
        <input type="text" name="username" placeholder="Choose a Username" required>
        <input type="password" name="password" placeholder="Choose a Password" required>
        <button type="submit" name="signup">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</body>
</html>';
?>
