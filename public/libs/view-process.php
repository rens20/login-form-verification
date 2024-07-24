<?php
session_start();
include('../../config/conn.php');

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'You must be logged in to view a post.';
    echo json_encode($response);
    exit;
}

// Get the JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Initialize post_id and user_id
$postId = isset($input['postId']) ? $input['postId'] : null;
$userId = $_SESSION['user_id'];

// Check if post_id is set
if (is_null($postId)) {
    $response['message'] = 'Post ID is missing.';
    echo json_encode($response);
    exit;
}

// Check if the user has already viewed this post
$sql = "SELECT COUNT(*) FROM post_views WHERE post_id = :post_id AND user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$viewed = $stmt->fetchColumn();

if ($viewed == 0) {
    // Increment the view count
    $sql = "UPDATE posts SET views = views + 1 WHERE id = :post_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $stmt->execute();

    // Record the view
    $sql = "INSERT INTO post_views (post_id, user_id) VALUES (:post_id, :user_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
}

// Return the updated view count
$sql = "SELECT views FROM posts WHERE id = :post_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
$stmt->execute();
$viewCount = $stmt->fetchColumn();

echo json_encode(['success' => true, 'viewCount' => $viewCount]);
?>
