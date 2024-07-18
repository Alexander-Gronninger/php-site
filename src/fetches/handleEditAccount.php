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
$newPassword = isset($_POST['newPassword']) ? trim($_POST['newPassword']) : '';

if (empty($newPassword)) {
    http_response_code(400); // Bad request
    echo json_encode(['error' => 'New password is required']);
    exit;
}

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Update user's password
    $sql = "UPDATE users SET password = :password WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
