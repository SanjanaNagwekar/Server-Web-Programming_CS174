<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);

    if (logInUser($username, $password, $dbConnection)) {
        header("Location: home.php");
        exit;
    } else {
        $login_error = "Incorrect username/password combination.";
    }
}

echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login to your account</h1>
HTML;
    printErrorMessage($login_error);
echo <<<HTML
    <form method="post" action="login.php" onsubmit="return validateLoginForm(this);">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="signup.php">Sign up here</a>.</p>
    <script src="validate_functions.js"></script>
</body>
</html>
HTML;
?>
