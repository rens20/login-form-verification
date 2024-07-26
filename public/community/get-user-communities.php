<?php
session_start();
include('../config/conn.php');

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("
        SELECT c.id, c.name 
        FROM communities c
        JOIN community_members cm ON c.id = cm.community_id
        WHERE cm.user_id = :user_id
    ");
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $communities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($communities);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
