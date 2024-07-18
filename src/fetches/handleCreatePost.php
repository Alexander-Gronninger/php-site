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
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    $categoryId = isset($_POST['category']) ? (int)$_POST['category'] : 0;

    if (empty($title) || empty($content) || $categoryId <= 0) {
        http_response_code(400); // Bad request
        echo json_encode(['error' => 'Title, content, and category are required']);
        exit;
    }

    try {
        // Insert post
        $sql = "INSERT INTO posts (user_id, title, content, category_id, created_at) VALUES (:user_id, :title, :content, :category_id, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        // Prepare JSON response
        $response = [
            'success' => true,
            'message' => 'Post created successfully'
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
