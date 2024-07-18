<?php
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
    $commentId = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;

    if ($commentId <= 0) {
        $response = ['error' => 'Invalid comment ID'];
        http_response_code(400); // Bad Request
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    try {
        // Fetch comment details to verify author
        $sql = "SELECT user_id FROM comments WHERE id = :comment_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':comment_id', $commentId, PDO::PARAM_INT);
        $stmt->execute();
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$comment || $comment['user_id'] !== $_SESSION['user_id']) {
            // User is not the author of the comment
            $response = [
                'error' => 'Unauthorized access'
            ];
            http_response_code(403); // Forbidden
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Delete comment
        $sqlDelete = "DELETE FROM comments WHERE id = :comment_id";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $stmtDelete->bindValue(':comment_id', $commentId, PDO::PARAM_INT);
        $stmtDelete->execute();

        // Prepare JSON response
        $response = [
            'success' => true,
            'message' => 'Comment deleted successfully'
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
