<?php
session_start();
include('../../config/conn.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize response array
    $response = ['status' => 'error', 'message' => 'An unexpected error occurred.'];

    // Check if required fields are present
    if (empty($_POST['title']) || empty($_POST['text']) || !isset($_SESSION['user_id'])) {
        $response['message'] = 'All fields are required.';
        echo json_encode($response);
        exit;
    }

    $title = trim($_POST['title']);
    $text = trim($_POST['text']);
    $userId = $_SESSION['user_id'];

    // Handle image upload
    $imageFile = null;
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $response['message'] = 'File is not an image.';
            echo json_encode($response);
            exit;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 5000000) { // 5MB limit
            $response['message'] = 'Sorry, your file is too large.';
            echo json_encode($response);
            exit;
        }

        // Allow certain file formats
        $allowed_formats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_formats)) {
            $response['message'] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
            echo json_encode($response);
            exit;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $response['message'] = 'Sorry, file already exists.';
            echo json_encode($response);
            exit;
        }

        // Try to upload file
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $response['message'] = 'Sorry, there was an error uploading your file.';
            echo json_encode($response);
            exit;
        }

        $imageFile = basename($target_file);
    }

    try {
        $sql = "INSERT INTO posts (title, text, image, created_at, tbl_user_id) VALUES (:title, :text, :image, NOW(), :user_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':text', $text, PDO::PARAM_STR);
        $stmt->bindParam(':image', $imageFile, PDO::PARAM_STR); // This can be NULL
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Post created successfully!';
        } else {
            $errorInfo = $stmt->errorInfo();
            $response['message'] = 'Database error: ' . $errorInfo[2];
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }

    echo json_encode($response);
}
?>
