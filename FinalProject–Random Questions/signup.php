<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

$signup_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitizeInput($_POST['name']);
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);

    if (!checkUsernameUnique($username, $dbConnection)) {
        $signup_error = "This username is already taken.";
    } else if (signUpUser($name, $username, $password, $dbConnection)) {
        $_SESSION['name'] = $name;
        header("Location: login.php");
        exit();
    } else {
        $signup_error = "An error occurred. Please try again.";
    }
}

echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
</head>
<body>
    <h1>Create a new account</h1>
HTML;
    printErrorMessage($signup_error);
echo <<<HTML
    <form method="post" action="signup.php" onsubmit="return validateSignupForm(this);">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password (Min. 6 characters)" required>
        <button type="submit">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
    <script src="validate_functions.js"></script>
</body>
</html>
HTML;
?>
