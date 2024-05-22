<?php

require_once 'config.php';

function sanitizeInput($input) {
    global $dbConnection;
    return mysqli_real_escape_string($dbConnection, trim($input));
}

function handleFileUpload($userId, $threadName, $fileContent, $mysqli) {
    $stmt = $mysqli->prepare("INSERT INTO threads (user_id, thread_name, file_content) VALUES (?, ?, ?)");
    $stmt->bind_param('iss', $userId, $threadName, $fileContent);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        echo "Error occurred, please try again!";
        $stmt->close();
        return false;
    }
}

function sanitizeForHTML($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function sanitizeForSQL($input, $mysqli) {
    return $mysqli->real_escape_string($input);
}

function logInUser($username, $password, $mysqli) {
    $stmt = $mysqli->prepare("SELECT user_id, username, password FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['userDisplayName'] = $user['username'];
            $result->free(); 
            $stmt->close();
            return true;
        }
    }
    $result->free();
    $stmt->close();
    return false;
}

function signUpUser($username, $password, $mysqli) {
    $stmt = $mysqli->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertStmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $insertStmt->bind_param('ss', $username, $hashedPassword);
        if ($insertStmt->execute()) {
            $_SESSION['userId'] = $insertStmt->insert_id;
            return true;
        }
    } else {
      return false;

    }
}

function isUserLoggedIn() {
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
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

function fetchUserThreads($userId, $mysqli) {
    $stmt = $mysqli->prepare("SELECT thread_id, thread_name, file_content FROM threads WHERE user_id = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $threads = [];
    while ($row = $result->fetch_assoc()) {
        $threads[] = $row;
    }
    $result->free();
    $stmt->close();
    return $threads;
}

function printErrorMessage($message) {
    if (!empty($message)) {
        echo '<p style="color: red;">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>';
    }
}

?>
