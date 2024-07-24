<?php
session_start();
include('../../config/conn.php');

$response = ['success' => false, 'message' => '', 'username' => '', 'comment' => ''];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'You must be logged in to comment.';
    echo json_encode($response);
    exit;
}

$userId = $_SESSION['user_id'];
$postId = $_POST['postId'];
$comment = $_POST['comment'];

// Validate inputs
if (empty($postId) || empty($comment)) {
    $response['message'] = 'Invalid inputs.';
    echo json_encode($response);
    exit;
}

// Insert comment into the database
$sql = "INSERT INTO comments (post_id, user_id, comment) VALUES (:post_id, :user_id, :comment)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':post_id', $postId);
$stmt->bindParam(':user_id', $userId);
$stmt->bindParam(':comment', $comment);

if ($stmt->execute()) {
    // Fetch the username of the commenter
    $sql = "SELECT username FROM tbl_user WHERE tbl_user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $username = $stmt->fetchColumn();

    $response['success'] = true;
    $response['username'] = ($username);
    $response['comment'] = ($comment);
} else {
    $response['message'] = 'Failed to add comment. Please try again later.';
}

echo json_encode($response);
?>
