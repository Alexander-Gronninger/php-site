<?php
session_start();

// Define the base directory
define('BASE_DIR', __DIR__ . '/../utils/');

// Include the database connection file
require_once BASE_DIR . 'db_connection.php';

$userId = $_SESSION['user_id'];

try {
    // Begin a transaction - needed cus multiple tables are changed at the same time
    $pdo->beginTransaction();

    // Delete related entries from banned_users, comments, and posts tables
    $sqlDeleteBannedUsers = "DELETE FROM banned_users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sqlDeleteBannedUsers);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $sqlDeleteComments = "DELETE FROM comments WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sqlDeleteComments);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $sqlDeletePosts = "DELETE FROM posts WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sqlDeletePosts);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // Finally, delete the user from the users table
    $sqlDeleteUser = "DELETE FROM users WHERE id = :user_id";
    $stmt = $pdo->prepare($sqlDeleteUser);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // Commit the transaction
    $pdo->commit();

    // Log the user out after deleting the account
    session_destroy();

    // Return success response
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    // Rollback the transaction if there is an error
    $pdo->rollBack();
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
