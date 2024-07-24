<?php
session_start();
include('../../config/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $profileImage = '';

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/';
        $uploadFile = $uploadDir . basename($_FILES['profile_image']['name']);
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
            $profileImage = basename($_FILES['profile_image']['name']);
        } else {
            echo "Failed to upload image.";
            exit;
        }
    }

    // Update user information in the database
    $sql = "UPDATE tbl_user SET first_name = :first_name, last_name = :last_name";
    if ($profileImage) {
        $sql .= ", profile_image = :profile_image";
    }
    $sql .= " WHERE tbl_user_id = :tbl_user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':first_name', $firstName);
    $stmt->bindParam(':last_name', $lastName);
    $stmt->bindParam(':tbl_user_id', $userId);
    if ($profileImage) {
        $stmt->bindParam(':profile_image', $profileImage);
    }

    if ($stmt->execute()) {
        echo "Profile updated successfully!";
        header('Location: profile.php'); // Redirect back to profile page after update
        exit;
    } else {
        echo "Failed to update profile.";
    }
}
?>
