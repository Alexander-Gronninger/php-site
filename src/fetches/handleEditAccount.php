<?php
// handleUpdatePassword.php

session_start();

// Define the base directory
define('BASE_DIR', __DIR__ . '/../utils/');

// Include the database connection file
require_once BASE_DIR . 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Initialize variables
$userId = $_SESSION['user_id'];
$newPassword = isset($_POST['newPassword']) ? trim($_POST['newPassword']) : '';

if (empty($newPassword)) {
    http_response_code(400); // Bad request
    echo json_encode(['error' => 'New password is required']);
    exit;
}

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

try {
    // Update user's password
    $sql = "UPDATE users SET password = :password WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // Respond with success
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
