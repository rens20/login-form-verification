<?php
session_start();
include('../../config/conn.php');

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'You must be logged in to like a post.';
    echo json_encode($response);
    exit;
}

$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$postId = $data['postId']; // Ensure this is being passed correctly

// Validate postId
if (!is_numeric($postId)) {
    $response['message'] = 'Invalid post ID.';
    echo json_encode($response);
    exit;
}

// Check if the user has already liked the post
$sql = "SELECT id FROM likes WHERE post_id = :post_id AND user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':post_id', $postId);
$stmt->bindParam(':user_id', $userId);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $response['message'] = 'You have already liked this post.';
    echo json_encode($response);
    exit;
}

// Insert the like into the database
$sql = "INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user_id)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':post_id', $postId);
$stmt->bindParam(':user_id', $userId);

if ($stmt->execute()) {
    // Get the updated like count
    $sql = "SELECT COUNT(*) as likeCount FROM likes WHERE post_id = :post_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $postId);
    $stmt->execute();
    $likeCount = $stmt->fetch(PDO::FETCH_ASSOC)['likeCount'];

    $response['success'] = true;
    $response['likeCount'] = $likeCount;
} else {
    $response['message'] = 'Failed to like the post. Please try again later.';
}

echo json_encode($response);
?>
