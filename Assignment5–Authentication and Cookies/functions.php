<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ONE_MONTH_IN_SECONDS', 2592000);
define('COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('SECURE_FLAG', false);
define('HTTP_ONLY_FLAG', true);

function sanitizeForHTML($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function sanitizeInput($input, $mysqli) {
    return $mysqli->real_escape_string($input);
}

function logInUser($username, $password, $mysqli) {
    $stmt = $mysqli->prepare("SELECT id, password, real_name FROM credentials WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            session_regenerate_id();
            $_SESSION['userId'] = $user['id'];
            $_SESSION['userDisplayName'] = $user['real_name'];
            return true;
        }
    }
    return false;
}

function signUpUser($username, $password, $realName, $mysqli) {
    $stmt = $mysqli->prepare("SELECT id FROM credentials WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertStmt = $mysqli->prepare("INSERT INTO credentials (username, password, real_name) VALUES (?, ?, ?)");
        $insertStmt->bind_param('sss', $username, $hashedPassword, $realName);
        if ($insertStmt->execute()) {
            $_SESSION['userId'] = $insertStmt->insert_id;
            $_SESSION['userDisplayName'] = $realName;
            return true;
        }
    } else {
        return false;
    }
}


function logOutUser() {
    setcookie('real_name', '', time() - ONE_MONTH_IN_SECONDS, COOKIE_PATH, COOKIE_DOMAIN, SECURE_FLAG, HTTP_ONLY_FLAG);
    $_SESSION = array();
    session_destroy();
}

function addPost($title, $content, $userId, $mysqli) {
    $stmt = $mysqli->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param('iss', $userId, $title, $content);
    return $stmt->execute();
}

function getUserPosts($userId, $mysqli) {
    $stmt = $mysqli->prepare("SELECT title, content FROM posts WHERE user_id = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    return $stmt->get_result();
}

function isUserLoggedIn() {
    return isset($_SESSION['userId']) && $_SESSION['userId'] > 0;
}

function setRealNameCookie($name) {
    $cleanName = sanitizeForHTML($name);
    setcookie('real_name', $cleanName, time() + ONE_MONTH_IN_SECONDS, COOKIE_PATH, COOKIE_DOMAIN, SECURE_FLAG, HTTP_ONLY_FLAG);
}

function printErrorMessage($message) {
    if (!empty($message)) {
        echo '<p style="color: red;">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>';
    }
}

?>
