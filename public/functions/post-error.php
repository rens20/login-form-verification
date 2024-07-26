<?php
$status = $_GET['status'] ?? 'unknown_error';

switch ($status) {
    case 'empty_fields':
        $message = "Title and text fields cannot be empty.";
        break;
    case 'invalid_file_type':
        $message = "Invalid file type. Allowed types are JPEG, PNG, and GIF.";
        break;
    case 'file_size_exceeded':
        $message = "File size exceeds 2 MB.";
        break;
    case 'upload_failure':
        $message = "Failed to upload the file.";
        break;
    case 'db_error':
        $message = "Database error occurred.";
        break;
    default:
        $message = "An unknown error occurred.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
</head>
<body>
    <h1>Error</h1>
    <p><?php echo htmlspecialchars($message); ?></p>
    <a href="../user-post.php">Go back</a>
</body>
</html>
