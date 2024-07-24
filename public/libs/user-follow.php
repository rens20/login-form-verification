<?php
session_start();
include('../../config/conn.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$loggedInUserId = $_SESSION['user_id'];
$profileUserId = isset($_POST['profile_user_id']) ? intval($_POST['profile_user_id']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if (!in_array($action, ['follow', 'unfollow'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    exit;
}

try {
    $conn->beginTransaction();

    if ($action === 'follow') {
        $sql = "INSERT INTO followers (follower_id, following_id) VALUES (:follower_id, :following_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':follower_id', $loggedInUserId);
        $stmt->bindParam(':following_id', $profileUserId);
        $stmt->execute();

        $sql = "INSERT INTO notifications (user_id, follower_id, notification_type) VALUES (:user_id, :follower_id, 'follow')";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $profileUserId);
        $stmt->bindParam(':follower_id', $loggedInUserId);
        $stmt->execute();
    } elseif ($action === 'unfollow') {
        $sql = "DELETE FROM followers WHERE follower_id = :follower_id AND following_id = :following_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':follower_id', $loggedInUserId);
        $stmt->bindParam(':following_id', $profileUserId);
        $stmt->execute();
    }

    $sql = "SELECT (SELECT COUNT(*) FROM followers WHERE following_id = :user_id) as followers_count,
                   (SELECT COUNT(*) FROM followers WHERE follower_id = :user_id) as following_count";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $profileUserId);
    $stmt->execute();
    $counts = $stmt->fetch(PDO::FETCH_ASSOC);

    $conn->commit();

    echo json_encode(['success' => true, 'followers_count' => $counts['followers_count'], 'following_count' => $counts['following_count']]);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
