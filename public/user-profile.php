<?php
session_start();
include('../config/conn.php');
include('../model/header.php');
include('../model/sidebar.php');
include('./functions/user_functions.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<div class='flex items-center justify-center min-h-screen bg-gray-100'><p class='text-red-500'>You are not logged in.</p></div>";
    exit;
}

$userId = $_SESSION['user_id'];

// Validate that the user ID is valid
$sql = "SELECT tbl_user_id FROM tbl_user WHERE tbl_user_id = :tbl_user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':tbl_user_id', $userId);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    echo "<div class='flex items-center justify-center min-h-screen bg-gray-100'><p class='text-red-500'>Invalid user.</p></div>";
    exit;
}

$userDetails = getUserDetails($userId, $conn);

// Define an array of image paths
$profileImages = [
    '../images/profile-user.png',
    '../images/profile2.png',
    '../images/profile3.png',
    '../images/profile4.png',
    '../images/profile5.png'
];

// Randomly select an image
$randomImage = $profileImages[array_rand($profileImages)];
?>

<!-- Tailwind CSS centered container -->
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <?php
        if ($userDetails) {
            echo "
            <div class='flex flex-col items-center'>
                <img src='$randomImage' alt='Profile Picture' class='w-24 h-24 rounded-full mb-4'>
                <h1 class='text-2xl font-bold mb-4 text-center'>" . htmlspecialchars($userDetails['first_name']) . " " . htmlspecialchars($userDetails['last_name']) . "</h1>
                <div class='flex space-x-4'>
                    <!-- Remove hover effect and set buttons to black -->
                    <button class='bg-black text-white px-4 py-2 rounded'>Follow</button>
                    <button class='bg-black text-white px-4 py-2 rounded'>Message</button>
                </div>
            </div>";
        } else {
            echo "<p class='text-red-500'>User not found.</p>";
        }
        ?>
    </div>
</div>

<?php include('../model/footer.php'); ?>
