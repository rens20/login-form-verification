<?php
session_start();
include('../../config/conn.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to join or leave a community.";
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $communityId = isset($_POST['community_id']) ? intval($_POST['community_id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'join') {
        // Join the community
        $sql = "INSERT INTO community_memberships (user_id, community_id) VALUES (:userId, :communityId)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':communityId', $communityId, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect to the communities page with a success message
        header('Location: ../my-communities.php?message=Joined successfully');
    } elseif ($action === 'leave') {
        // Leave the community
        $sql = "DELETE FROM community_memberships WHERE user_id = :userId AND community_id = :communityId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':communityId', $communityId, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect to the communities page with a success message
        header('Location: ../user-community.php?message=Left successfully');
    } else {
        // Invalid action
        echo "Invalid action.";
    }
} else {
    echo "Invalid request method.";
}
