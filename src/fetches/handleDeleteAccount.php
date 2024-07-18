<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$host = 'localhost';
$dbname = 'myphpproject';
$dbUsername = 'root';
$dbPassword = 'dinmor1234';
$userId = $_SESSION['user_id'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Soft delete the user account
    $sql = "UPDATE users SET is_deleted = 1 WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // Also, you might want to log the user out after deleting the account
    session_destroy();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
