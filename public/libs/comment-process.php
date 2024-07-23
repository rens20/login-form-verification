<?php
session_start();
include('../../config/conn.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to comment.";
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['postId']) || !isset($_POST['comment'])) {
        echo "Invalid request.";
        exit;
    }

    $postId = $_POST['postId'];
    $comment = $_POST['comment'];

    // Validate that the post ID is valid
    $sql = "SELECT id FROM posts WHERE id = :post_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $postId);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        echo "Invalid post.";
        exit;
    }

    // Insert comment into the database using PDO
    $sql = "INSERT INTO comments (post_id, user_id, comment) VALUES (:post_id, :user_id, :comment)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $postId);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':comment', $comment);

    if ($stmt->execute()) {
        header("location: ../user-page.php");
    } else {
        echo "Error posting comment.";
    }
}
?>
