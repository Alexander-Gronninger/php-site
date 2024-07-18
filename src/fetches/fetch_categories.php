<?php
session_start();
// Database connection details
$host = 'localhost';
$dbname = 'myphpproject';
$username = 'root';
$password = 'dinmor1234';

// PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to retrieve categories
    $sql = "SELECT name FROM categories";

    // Prepare and execute the query
    $stmt = $pdo->query($sql);

    // Fetch all categories as associative array
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['categories' => $categories]);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
