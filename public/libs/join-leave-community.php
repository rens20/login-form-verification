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
        // Fetch the community name
        $sqlFetchCommunity = "SELECT name FROM communities WHERE id = :communityId";
        $stmtFetchCommunity = $conn->prepare($sqlFetchCommunity);
        $stmtFetchCommunity->bindParam(':communityId', $communityId, PDO::PARAM_INT);
        $stmtFetchCommunity->execute();
        $community = $stmtFetchCommunity->fetch(PDO::FETCH_ASSOC);

        if ($community) {
            $communityName = $community['name'];

            // Join the community
            $sqlJoin = "INSERT INTO community_memberships (user_id, community_id, community_name) VALUES (:userId, :communityId, :communityName)";
            $stmtJoin = $conn->prepare($sqlJoin);
            $stmtJoin->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmtJoin->bindParam(':communityId', $communityId, PDO::PARAM_INT);
            $stmtJoin->bindParam(':communityName', $communityName, PDO::PARAM_STR);
            $stmtJoin->execute();

            // Redirect to the communities page with a success message
            header('Location: ../my-communities.php?message=Joined successfully');
        } else {
            echo "Community not found.";
        }
    } elseif ($action === 'leave') {
        // Leave the community
        $sqlLeave = "DELETE FROM community_memberships WHERE user_id = :userId AND community_id = :communityId";
        $stmtLeave = $conn->prepare($sqlLeave);
        $stmtLeave->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmtLeave->bindParam(':communityId', $communityId, PDO::PARAM_INT);
        $stmtLeave->execute();

        // Redirect to the communities page with a success message
        header('Location: ../user-community.php?message=Left successfully');
    } else {
        // Invalid action
        echo "Invalid action.";
    }
} else {
    echo "Invalid request method.";
}
