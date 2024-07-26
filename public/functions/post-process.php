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

    // Validate input
    if (empty($title) || empty($text)) {
        header('Location: post-error.php?status=empty_fields');
        exit;
    }

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
            header("Location: post-error.php?status=invalid_file_type");
            exit;
        }

        // Check file size
        if ($imageSize > $maxFileSize) {
            header("Location: post-error.php?status=file_size_exceeded");
            exit;
        }

        // Sanitize the file name
        $imageName = preg_replace("/[^a-zA-Z0-9.]/", "", basename($imageName));
        $imagePath = '../../uploads/' . $imageName;

        // Ensure the uploads directory exists
        if (!is_dir('../../uploads/')) {
            mkdir('../../uploads/', 0777, true);
        }

        // Move the uploaded file to the 'uploads' directory
        if (!move_uploaded_file($imageTmpPath, $imagePath)) {
            header("Location: post-error.php?status=upload_failure");
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
        header('Location: post-error.php?status=db_error');
        exit;
    }
}
?>

