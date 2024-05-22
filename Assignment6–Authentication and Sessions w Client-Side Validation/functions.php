<?php

require_once 'config.php';

function sanitizeInput($input) {
    global $dbConnection;
    return mysqli_real_escape_string($dbConnection, trim($input));
}

function sanitizeForHTML($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function sanitizeForSQL($input, $mysqli) {
    return $mysqli->real_escape_string($input);
}

function checkEmailUnique($email, $mysqli) {
    $stmt = $mysqli->prepare("SELECT email FROM credentials WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->fetch_assoc()) {
        return false;
    }
    return true;
}

function logInUser($username, $password, $mysqli) {
    $username = sanitizeForSQL($username, $mysqli);
    $password = sanitizeForSQL($password, $mysqli);

    $stmt = $mysqli->prepare("SELECT student_id, password, name FROM credentials WHERE email = ? OR student_id = ?");
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['student_id'];
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

function signUpUser($name, $email, $student_id, $password, $mysqli) {
    $name = sanitizeForSQL($name, $mysqli);
    $email = sanitizeForSQL($email, $mysqli);
    $student_id = sanitizeForSQL($student_id, $mysqli);
    $password = sanitizeForSQL($password, $mysqli);

    $stmt = $mysqli->prepare("SELECT student_id FROM credentials WHERE email = ? OR student_id = ?");
    $stmt->bind_param('ss', $email, $student_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertStmt = $mysqli->prepare("INSERT INTO credentials (name, email, student_id, password) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param('ssss', $name, $email, $student_id, $hashedPassword);
        if ($insertStmt->execute()) {
            $_SESSION['user_id'] = $student_id;
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

function getAdvisorInfo($studentID, $mysqli) {
    $lastTwoDigits = substr($studentID, -2);
    $query = "SELECT fullName, email, phone_number FROM advisors WHERE ? >= lowerBound AND ? <= upperBound";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $lastTwoDigits, $lastTwoDigits);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($advisor = $result->fetch_assoc()) {
        return "Advisor Name: " . $advisor['fullName'] . "<br>Email: " . $advisor['email'] . "<br>Phone: " . $advisor['phone_number'];
    } else {
        return "No advisor found for this ID.";
    }
}

function validateStudent($name, $studentID, $mysqli) {
    $stmt = $mysqli->prepare("SELECT name FROM credentials WHERE student_id = ? AND name = ?");
    $stmt->bind_param("ss", $studentID, $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->fetch_assoc()) {
        return true;
    } else {
        return false;
    }
}

?>
