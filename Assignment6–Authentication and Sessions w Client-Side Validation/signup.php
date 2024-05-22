<?php

session_start();
require_once 'functions.php';
require_once 'config.php';

$signup_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $student_id = sanitizeInput($_POST['student_id']);
    $password = sanitizeInput($_POST['password']);
    if (!checkEmailUnique($email, $dbConnection)) {
    $signup_error = "This email is already registered.";
    } else if (signUpUser($name, $email, $student_id, $password, $dbConnection)) {
        $_SESSION['name'] = $name;
        header("Location: login.php");
        exit();
    } else {
        $signup_error = "An error occurred or the email/student ID is already registered.";
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
echo '<form method="post" action="signup.php" onsubmit="return validate(this);">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="student_id" placeholder="Student ID (9 digits)" required pattern="\d{9}">
        <input type="password" name="password" placeholder="Password (Min. 6 characters)" required>
        <button type="submit">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>

<script src="validate_functions.js"></script>
</body>
</html>';
?>
