<?php
session_start();

// Define the base directory
define('BASE_DIR', __DIR__ . '/../utils/');

// Include the database connection file
require_once BASE_DIR . 'db_connection.php';

// Query to retrieve categories
$sql = "SELECT name FROM categories";

try {
    // Prepare and execute the query
    $stmt = $pdo->query($sql);

    // Fetch all categories as associative array
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['categories' => $categories]);

} catch (PDOException $e) {
    // Handle any errors that occur during the query execution
    http_response_code(500); // Set HTTP response code to 500 for server errors
    echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
}
?>
