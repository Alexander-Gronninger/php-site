<?php
session_start();

// Define the base directory
define('BASE_DIR', __DIR__ . '/../utils/');

// Include the database connection file
require_once BASE_DIR . 'db_connection.php';



// Initialize variables
$offset = isset($_GET['offset']) && is_numeric($_GET['offset']) ? $_GET['offset'] : 0; // Offset for posts
$limit = 5; // Number of posts to load per batch
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest'; // Sorting type (default: newest)
$category = isset($_GET['category']) && $_GET['category'] !== 'all' ? $_GET['category'] : null; // Category filter
$searchTerm = isset($_GET['search']) ? $_GET['search'] : null; // Search term
$searchTitle = isset($_GET['search_title']) && $_GET['search_title'] == 1 ? true : false; // Search by title flag
$searchContent = isset($_GET['search_content']) && $_GET['search_content'] == 1 ? true : false; // Search by content flag
$searchAuthor = isset($_GET['search_author']) && $_GET['search_author'] == 1 ? true : false; // Search by author flag

// Validate and set sorting order
switch ($sort) {
    case 'oldest':
        $orderBy = 'ORDER BY posts.created_at ASC';
        break;
    case 'alphabetical':
        $orderBy = 'ORDER BY posts.title ASC';
        break;
    case 'newest':
    default:
        $orderBy = 'ORDER BY posts.created_at DESC';
        break;
}

// Query to retrieve posts with pagination and join with categories
$sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, users.username as author, categories.name as category_name, categories.description as category_description
        FROM posts
        JOIN categories ON posts.category_id = categories.id
        JOIN users ON posts.user_id = users.id";

$whereClauses = [];
$params = [];

if ($category) {
    $whereClauses[] = "categories.name = :category";
    $params[':category'] = $category;
}

if ($searchTerm) {
    $searchTerm = "%{$searchTerm}%";
    
    // Build search conditions based on user preferences
    $searchConditions = [];
    if ($searchTitle) {
        $searchConditions[] = "posts.title LIKE :search_title";
        $params[':search_title'] = $searchTerm;
    }
    if ($searchContent) {
        $searchConditions[] = "posts.content LIKE :search_content";
        $params[':search_content'] = $searchTerm;
    }
    if ($searchAuthor) {
        $searchConditions[] = "users.username LIKE :search_author";
        $params[':search_author'] = $searchTerm;
    }
    
    // Combine search conditions with OR operator
    if (!empty($searchConditions)) {
        $whereClauses[] = "(" . implode(" OR ", $searchConditions) . ")";
    }
}

if (!empty($whereClauses)) {
    $sql .= " WHERE " . implode(" AND ", $whereClauses);
}

$sql .= " $orderBy LIMIT :limit OFFSET :offset";

try {
    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->execute();

    // Fetch all posts as associative array
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['limit' => $limit, 'posts' => $posts]);

} catch (PDOException $e) {
    // Handle errors and output as JSON
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
