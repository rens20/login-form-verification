<?php
session_start();
include('../../config/conn.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to post.";
    exit;
}

$userId = $_SESSION['user_id'];

// Validate that the user ID is valid
$sql = "SELECT tbl_user_id FROM tbl_user WHERE tbl_user_id = :tbl_user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':tbl_user_id', $userId);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    echo "Invalid user.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $text = $_POST['text'];

    // Validate file upload
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 2 * 1024 * 1024; // 2 MB

    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imageSize = $_FILES['image']['size'];
        $imageType = $_FILES['image']['type'];

        // Check file type
        if (!in_array($imageType, $allowedTypes)) {
            header("location: ../error/error-404.php");
            exit;
        }

        // Check file size
        if ($imageSize > $maxFileSize) {
            echo "File size exceeds 2 MB.";
            exit;
        }
     // Sanitize the file name
        $imageName = preg_replace("/[^a-zA-Z0-9.]/", "", basename($imageName));
        $imagePath = '../../uploads/' . $imageName; // Added trailing slash

        // Ensure the uploads directory exists
        if (!is_dir('../../uploads/')) {
            echo "Uploads directory does not exist.";
            exit;
        }

        // Move the uploaded file to the 'uploads' directory
        if (!move_uploaded_file($imageTmpPath, $imagePath)) {
            echo "Failed to move uploaded file to: " . $imagePath;
            exit;
        }
    }

    // Insert post into the database using PDO
    $sql = "INSERT INTO posts (title, text, image, tbl_user_id) VALUES (:title, :text, :image, :tbl_user_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':text', $text);
    $stmt->bindParam(':image', $imagePath);
    $stmt->bindParam(':tbl_user_id', $userId);

    if ($stmt->execute()) {
        // Redirect to success page with status parameter
        header('Location: post-success.php?status=success');
        exit;
    } else {
        // Redirect to error page with status parameter
        header('Location: post-success.php?status=error');
        exit;
    }
}
?>
