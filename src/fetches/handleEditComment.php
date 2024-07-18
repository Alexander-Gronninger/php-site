<?php
// handleEditComment.php

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
    $commentId = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;
    $editedContent = isset($_POST['content']) ? trim($_POST['content']) : '';

    if (empty($editedContent)) {
        http_response_code(400); // Bad request
        echo json_encode(['error' => 'Content cannot be empty']);
        exit;
    }

    // Fetch comment details to verify author
    try {
        $sql = "SELECT user_id FROM comments WHERE id = :comment_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':comment_id', $commentId, PDO::PARAM_INT);
        $stmt->execute();
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$comment || $comment['user_id'] !== $_SESSION['user_id']) {
            // User is not the author of the comment
            http_response_code(403); // Forbidden
            echo json_encode(['error' => 'Unauthorized access']);
            exit;
        }

        // Update comment
        $sqlUpdate = "UPDATE comments SET content = :content WHERE id = :comment_id";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindValue(':content', $editedContent, PDO::PARAM_STR);
        $stmtUpdate->bindValue(':comment_id', $commentId, PDO::PARAM_INT);
        $stmtUpdate->execute();

        // Prepare JSON response
        $response = [
            'success' => true,
            'message' => 'Comment updated successfully'
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
