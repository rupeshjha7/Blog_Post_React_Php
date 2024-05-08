<?php
header('Access-Control-Allow-Headers: Content-Type'); 

require_once('../config/config.php');
require_once('../config/database.php');


$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);


if (empty($data['title']) || empty($data['content']) || empty($data['author'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Error: Missing or empty required parameter']);
    exit();
}


if (!isset($data['title']) || !isset($data['content']) || !isset($data['author'])) {
    http_response_code(400);
    die(json_encode(['message' => 'Error: Missing required parameter']));
}

$title = filter_var($data['title'], FILTER_SANITIZE_STRING);
$author = filter_var($data['author'], FILTER_SANITIZE_STRING);
$content = filter_var($data['content'], FILTER_SANITIZE_STRING);

$stmt = $conn->prepare('INSERT INTO blog_posts (title, content, author) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $title, $content, $author);

if ($stmt->execute()) {
    $id = $stmt->insert_id;
    http_response_code(201);
    echo json_encode(['message' => 'Post created successfully', 'id' => $id]);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Error creating post: ' . $stmt->error]);
}
$stmt->close();
$conn->close();