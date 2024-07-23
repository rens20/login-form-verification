<?php
session_start();


// Check for status in query string
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Success</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        // Show SweetAlert notification based on status
        document.addEventListener('DOMContentLoaded', function () {
            <?php if ($status == 'success'): ?>
                Swal.fire({
                    title: 'Success!',
                    text: 'Post created successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redirect to the post creation page or another desired page
                    window.location.href = '../user-post.php';
                });
            <?php else: ?>
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redirect back to the post creation page
                    window.location.href = '../user-post.php';
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
