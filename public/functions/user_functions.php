<?php
function getUserDetails($userId, $conn) {
    $sql = "SELECT first_name, last_name FROM tbl_user WHERE tbl_user_id = :tbl_user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':tbl_user_id', $userId);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}
?>
