<?php
require_once('../config/config.php');
require_once('../config/database.php');
header('Content-Type: application/json');
$allowedMethods = ['GET'];
$maxPostsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $maxPostsPerPage;
$countQuery = "SELECT COUNT(*) AS totalPosts FROM blog_posts";
$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalPosts = $countRow['totalPosts'];
if (!$countResult) {
    http_response_code(500); 
    echo json_encode(['message' => 'Error querying database for total posts count: ' . mysqli_error($conn)]);
    mysqli_close($conn);
    exit();
}

$query = "SELECT * FROM blog_posts ORDER BY publish_date DESC LIMIT $offset, $maxPostsPerPage";
$result = mysqli_query($conn, $query);


if (!$result) {
    http_response_code(500); 
    echo json_encode(['message' => 'Error querying database for paginated posts: ' . mysqli_error($conn)]);
    mysqli_close($conn);
    exit();
}


$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);


if (empty($posts)) {
    http_response_code(404); 
    echo json_encode(['message' => 'No posts found', 'totalPosts' => $totalPosts]);
} else {
    echo json_encode(['posts' => $posts, 'totalPosts' => $totalPosts]);
}

mysqli_close($conn);