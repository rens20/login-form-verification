<?php
function getUserDetails($userId, $conn) {
    $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE tbl_user_id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
