<?php

require_once 'config.php';

function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

function sanitizeInput($input) {
    global $dbConnection;
    return mysqli_real_escape_string($dbConnection, trim($input));
}

function sanitizeForHTML($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function checkUsernameUnique($username, $mysqli) {
    $stmt = $mysqli->prepare("SELECT username FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->fetch_assoc()) {
        $stmt->close();
        return false;
    }
    $stmt->close();
    return true;
}

function logInUser($username, $password, $mysqli) {
    $username = sanitizeForSQL($username, $mysqli);
    $password = sanitizeForSQL($password, $mysqli);

    $stmt = $mysqli->prepare("SELECT id, password, name FROM Users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $user['name'];
            $result->free();
            $stmt->close();
            return true;
        }
    }
    $result->free();
    $stmt->close();
    return false;
}

function signUpUser($name, $username, $password, $mysqli) {
    $name = sanitizeForSQL($name, $mysqli);
    $username = sanitizeForSQL($username, $mysqli);
    $password = sanitizeForSQL($password, $mysqli);

    $stmt = $mysqli->prepare("SELECT id FROM Users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertStmt = $mysqli->prepare("INSERT INTO Users (name, username, password) VALUES (?, ?, ?)");
        $insertStmt->bind_param('sss', $name, $username, $hashedPassword);
        if ($insertStmt->execute()) {
            $_SESSION['user_id'] = $mysqli->insert_id;
            $insertStmt->close();
            return true;
        }
        $insertStmt->close();
    }
    $stmt->close();
    return false;
}

function logOutUser() {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 2592000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

function printErrorMessage($message) {
    if (!empty($message)) {
        echo '<p style="color: red;">' . sanitizeForHTML($message) . '</p>';
    }
}

function sanitizeForSQL($input, $mysqli) {
    return $mysqli->real_escape_string($input);
}

function questionExists($dbConnection, $user_id, $question) {
    $stmt = $dbConnection->prepare("SELECT COUNT(*) FROM Questions WHERE user_id = ? AND question = ?");
    if ($stmt === false) {
        error_log('MySQL prepare error: ' . $dbConnection->error);
        die("An error occurred, please try again later.");
    }

    $stmt->bind_param("is", $user_id, $question);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count > 0;
}

function addQuestion($dbConnection, $user_id, $question) {
    $stmt = $dbConnection->prepare("INSERT INTO Questions (user_id, question) VALUES (?, ?)");
    if ($stmt === false) {
        error_log('MySQL prepare error: ' . $dbConnection->error);
        die("An error occurred, please try again later.");
    }

    $stmt->bind_param("is", $user_id, $question);
    $stmt->execute();
    $stmt->close();
}
?>
