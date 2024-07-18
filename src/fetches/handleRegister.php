<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define the base directory for includes
define('BASE_DIR', __DIR__ . '/../utils/');

// Include the database connection file
require_once BASE_DIR . 'db_connection.php';

// Get username, email, and password from POST request
$username = isset($_POST['registerUsername']) ? trim($_POST['registerUsername']) : '';
$email = isset($_POST['registerEmail']) ? trim($_POST['registerEmail']) : '';
$password = isset($_POST['registerPassword']) ? trim($_POST['registerPassword']) : '';

// Validate inputs (basic validation)
if (empty($username) || empty($email) || empty($password)) {
    http_response_code(400); // Bad request
    echo json_encode(['error' => 'All fields are required']);
    exit;
}

// Check if the email is valid
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); // Bad request
    echo json_encode(['error' => 'Invalid email address']);
    exit;
}

try {
    // Check if username or email already exists
    $sql = "SELECT COUNT(*) FROM users WHERE username = :username OR email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        http_response_code(400); // Bad request
        echo json_encode(['error' => 'Username or email already exists']);
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->execute();

    // Fetch the newly created user
    $userId = $pdo->lastInsertId();
    $sql = "SELECT username, password FROM users WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Log the user in
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = 'user'; // Default role, adjust as needed

        // Return JSON response indicating successful registration and login
        echo json_encode([
            'success' => true,
            'message' => 'Registration successful and user logged in'
        ]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Failed to log in user after registration']);
    }
} catch (PDOException $e) {
    // Return JSON response on database connection or query error
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
