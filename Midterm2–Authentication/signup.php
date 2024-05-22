<?php

session_start();
require_once 'functions.php';
require_once 'config.php';

$signup_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (signUpUser($username, $password, $dbConnection)) {
        header("Location: login.php");
        exit();
    } else {
        $signup_error = "Username is already taken or an error occurred.";
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
        <input type="text" name="username" placeholder="Choose a Username" required>
        <input type="password" name="password" placeholder="Choose a Password" required>
        <button type="submit" name="signup">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</body>
</html>';
?>
