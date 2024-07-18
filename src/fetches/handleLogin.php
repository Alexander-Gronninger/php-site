<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$host = 'localhost';
$dbname = 'myphpproject';
$dbUsername = 'root';
$dbPassword = 'dinmor1234';

// Get username and password from POST request
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Validate inputs (basic validation)
if (empty($username) || empty($password)) {
    http_response_code(400); // Bad request
    echo json_encode(['error' => 'Username and password are required']);
    exit;
}

try {
    // PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch user details
    $sql = "SELECT id, username, password, role FROM users WHERE username = :username AND is_deleted = 0 AND is_banned = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        // Store user data in session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Return JSON response indicating successful login
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Login successful']);

        error_log($_SESSION['user_id']);
        error_log($_SESSION['username']);
    } else {
        // Return JSON response indicating login failure
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Invalid username or password']);
    }
} catch (PDOException $e) {
    // Return JSON response on database connection or query error
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
