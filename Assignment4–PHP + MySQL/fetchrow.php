<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'login.php';

define('EMPTY_STRING', '');
define('NEW_LINE', PHP_EOL);

function dbConnect() {
    global $hn, $un, $pw, $db;
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) {
        die("ERROR: Oops, something went wrong!!" . htmlspecialchars($conn->connect_error));
    }
    return $conn;
}

function sanitize($conn, $var) {
    $value = $_POST[$var] ?? '';
    $value = htmlentities($value, ENT_QUOTES, 'UTF-8');
    $value = $conn->real_escape_string($value);
    $value = htmlspecialchars($value);
    return $value;
}

function displayUserBalances($conn) {
    $tableRows = generateTableRows($conn);
    echo "<table border='1'><tr><th>Email</th><th>Balance</th></tr>" . $tableRows . "</table>";
}

function generateTableRows($conn) {
    $stmt = $conn->prepare("SELECT email, balance FROM users ORDER BY email");
    $stmt->execute();
    $result = $stmt->get_result();
    $tableRows = '';
    while ($row = $result->fetch_assoc()) {
        $tableRows .= "<tr><td>" . htmlspecialchars($row['email']) . "</td><td>" . htmlspecialchars($row['balance']) . "</td></tr>";
    }
    $result->close();
    return $tableRows;
}

function processTransactions($conn, $email, $fileContent) {
    $transactions = explode(NEW_LINE, $fileContent);
    $balanceUpdate = 0;
    $errorMessages = [];
    foreach ($transactions as $transaction) {
        if (is_numeric($transaction)) {
            $balanceUpdate += (float)$transaction;
        } else {
            $errorMessages[] = "Invalid transaction skipped: " . htmlspecialchars($transaction);
        }
    }
    if ($balanceUpdate !== 0) {
        $stmt = $conn->prepare("INSERT INTO users (email, balance) VALUES (?, ?) ON DUPLICATE KEY UPDATE balance=balance+?");
        $stmt->bind_param("sdd", $email, $balanceUpdate, $balanceUpdate);
        if (!$stmt->execute()) {
            die("Execute failed: " . htmlspecialchars($stmt->error));
        }
        $stmt->close();
    }
    return $errorMessages;
}

$conn = dbConnect();
$errorMessages = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['transactionFile']) && isset($_POST['email'])) {
    $email = sanitize($conn, 'email');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("ERROR: Invalid email syntax!!!");
    }
    $fileContent = file_get_contents($_FILES['transactionFile']['tmp_name']);
    if ($fileContent === false) {
        die("ERROR: Unable to read file!!!");
    }
    $errorMessages = processTransactions($conn, $email, $fileContent);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Transactions</title>
</head>
<body>
    <?php if (!empty($errorMessages)): ?>
        <div class="error-messages">
            <?php foreach ($errorMessages as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <h1>User Balances</h1>
    <?php displayUserBalances($conn); ?>

    <h2>Upload Transaction File</h2>
    <form action="fetchrow.php" method="post" enctype="multipart/form-data">
        User Email: <input type="email" name="email" required><br>
        Transactions File: <input type="file" name="transactionFile" accept=".txt" required><br>
        <input type="submit" value="Upload">
    </form>
</body>
</html>

<?php $conn->close(); ?>
