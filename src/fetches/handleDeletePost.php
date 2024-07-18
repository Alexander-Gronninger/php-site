<?php
// handleDeletePost.php

session_start();

// Define the base directory
define('BASE_DIR', __DIR__ . '/../utils/');

// Include the database connection file
require_once BASE_DIR . 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        // User is not logged in
        $response = [
            'error' => 'User not logged in'
        ];
        http_response_code(401); // Unauthorized
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // Validate and sanitize inputs
    $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;

    if ($postId <= 0) {
        $response = ['error' => 'Invalid post ID'];
        http_response_code(400); // Bad Request
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    try {
        // Fetch post details to verify author
        $sql = "SELECT user_id FROM posts WHERE id = :post_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post || $post['user_id'] !== $_SESSION['user_id']) {
            // User is not the author of the post
            $response = [
                'error' => 'Unauthorized access'
            ];
            http_response_code(403); // Forbidden
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Delete post
        $sqlDelete = "DELETE FROM posts WHERE id = :post_id";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $stmtDelete->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmtDelete->execute();

        // Prepare JSON response
        $response = [
            'success' => true,
            'message' => 'Post deleted successfully'
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    } catch (PDOException $e) {
        // Handle database errors
        http_response_code(500); // Internal Server Error
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
