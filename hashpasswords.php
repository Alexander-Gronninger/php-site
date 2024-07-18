<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$host = 'localhost';
$dbname = 'myphpproject';
$username = 'root';
$password = 'dinmor1234';

try {
    // PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all users
    $stmt = $pdo->query("SELECT id, username, password FROM users WHERE is_deleted = 0 AND is_banned = 0");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Iterate through each user and hash their password
    foreach ($users as $user) {
        // Hash the plain text password
        $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);

        // Update the user's password in the database
        $updateStmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        $updateStmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
        $updateStmt->bindValue(':id', $user['id'], PDO::PARAM_INT);
        $updateStmt->execute();

        echo "Password for user {$user['username']} (ID: {$user['id']}) has been hashed.<br>";
    }

    echo "All passwords have been successfully hashed.";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
