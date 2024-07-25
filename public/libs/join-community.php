<?php
session_start();
include('../../config/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $community_id = $_POST['community_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO community_members (community_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $community_id, $user_id);

    if ($stmt->execute()) {
        echo "Joined community successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
