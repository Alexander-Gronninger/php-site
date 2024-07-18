<?php
session_start();

// Define the base directory
define('BASE_DIR', __DIR__ . '/../utils/');

// Include the database connection file
require_once BASE_DIR . 'db_connection.php';

// Get the post ID from the request
$postId = isset($_GET['post_id']) && is_numeric($_GET['post_id']) ? $_GET['post_id'] : 0;

try {
    // Fetch the post details
    $sql = "SELECT 
            posts.id, 
            posts.title, 
            posts.content, 
            posts.created_at, 
            users.id as author_id,
            users.username as author, 
            categories.name as category_name, 
            categories.description as category_description
        FROM posts
        JOIN categories ON posts.category_id = categories.id
        JOIN users ON posts.user_id = users.id
        WHERE posts.id = :post_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch the comments for the post
    $sql = "SELECT 
            comments.content, 
            comments.created_at, 
            users.id as author_id,
            users.username as author, 
            comments.is_deleted, 
            comments.delete_reason
        FROM comments
        JOIN users ON comments.user_id = users.id
        WHERE comments.post_id = :post_id
        ORDER BY comments.created_at ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Process comments to handle deleted comments
    foreach ($comments as &$comment) {
        if ($comment['is_deleted']) {
            $comment['author'] = '*deleted*';
            $comment['content'] = '*this comment was deleted: ' . $comment['delete_reason'] . '*';
        }
        // Remove the is_deleted and delete_reason fields from the response
        unset($comment['is_deleted'], $comment['delete_reason']);
    }

    // Ensure no other output
    ob_clean();

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['post' => $post, 'comments' => $comments]);

} catch (PDOException $e) {
    // Clean the output buffer and display an error message as JSON
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
