<?php
session_start();
include('../../config/conn.php');

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("
        SELECT c.*, 
            (SELECT COUNT(*) FROM community_members cm WHERE cm.community_id = c.id AND cm.user_id = :user_id) AS is_member 
        FROM communities c
    ");
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $communities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($communities);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
