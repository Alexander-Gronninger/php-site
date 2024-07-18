<?php
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

$userId = $_SESSION['user_id'];

try {
    // Fetch user posts
    $sql = "SELECT id, title FROM posts WHERE user_id = :user_id AND is_deleted = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch user comments
    $sql = "SELECT id, content, post_id FROM comments WHERE user_id = :user_id AND is_deleted = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode(['posts' => $posts, 'comments' => $comments]);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
