<?php
// handleEditPost.php

session_start();

// Include the database connection file
require_once '../src/utils/db_connection.php'; // Adjust the path if necessary

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        // User is not logged in
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'User not logged in']);
        exit;
    }

    // Validate inputs
    $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $editedTitle = isset($_POST['title']) ? trim($_POST['title']) : '';
    $editedContent = isset($_POST['content']) ? trim($_POST['content']) : '';

    if (empty($editedTitle) || empty($editedContent)) {
        http_response_code(400); // Bad request
        echo json_encode(['error' => 'Title and content are required']);
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
            http_response_code(403); // Forbidden
            echo json_encode(['error' => 'Unauthorized access']);
            exit;
        }

        // Update post
        $sqlUpdate = "UPDATE posts SET title = :title, content = :content WHERE id = :post_id";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindValue(':title', $editedTitle, PDO::PARAM_STR);
        $stmtUpdate->bindValue(':content', $editedContent, PDO::PARAM_STR);
        $stmtUpdate->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmtUpdate->execute();

        // Prepare JSON response
        $response = [
            'success' => true,
            'message' => 'Post updated successfully'
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
