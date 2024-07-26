<?php
session_start();
include('../../config/conn.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['community_name']);
    $description = trim($_POST['community_description']);
    $created_by = $_SESSION['user_id'];

    // Basic validation
    if (empty($name) || empty($description)) {
        echo "Name and description are required.";
        exit;
    }

    // Handle image upload
    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($_FILES["community_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a valid image
    $check = getimagesize($_FILES["community_image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        exit;
    }

    // Check file size
    if ($_FILES["community_image"]["size"] > 5000000) { // 5MB limit
        echo "Sorry, your file is too large.";
        exit;
    }

    // Allow certain file formats
    $allowed_formats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_formats)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        exit;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        exit;
    }

    // Try to upload file
    if (!move_uploaded_file($_FILES["community_image"]["tmp_name"], $target_file)) {
        echo "Sorry, there was an error uploading your file.";
        exit;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO communities (name, description, created_by, image) VALUES (:name, :description, :created_by, :image)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':created_by', $created_by, PDO::PARAM_INT);
        $stmt->bindParam(':image', basename($target_file), PDO::PARAM_STR); // Use only the file name

        if ($stmt->execute()) {
            echo "Community created successfully!";
        } else {
            // Output detailed error information
            $errorInfo = $stmt->errorInfo();
            echo "Error: " . $errorInfo[2];
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
