<?php
session_start();

// Define the base directory
define('BASE_DIR', __DIR__ . '/../utils/');

// Include the database connection file
require_once BASE_DIR . 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        // User is not logged in
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'User not logged in']);
        exit;
    }

    // Validate inputs
    $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';

    if (empty($content)) {
        http_response_code(400); // Bad request
        echo json_encode(['error' => 'Content is required']);
        exit;
    }

    try {
        // Insert comment
        $sql = "INSERT INTO comments (post_id, user_id, content, created_at) VALUES (:post_id, :user_id, :content, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->execute();

        // Prepare JSON response
        $response = [
            'success' => true,
            'message' => 'Comment added successfully'
        ];

        header('Content-Type: application/json');
        echo json_encode($response);

    } catch (PDOException $e) {
        // Handle database errors
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
