<?php
session_start();
include('../config/conn.php');
include('../model/header.php');
include('../model/sidebar.php');
?>

<?php
include('./functions/user_functions.php');

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
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
                <h1 class='text-2xl font-bold mb-4 text-center'>" . ($userDetails['first_name']) . " " . ($userDetails['last_name']) . "</h1>
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

<?php
} else {
    echo "<div class='flex items-center justify-center min-h-screen bg-gray-100'><p class='text-red-500'>You are not logged in.</p></div>";
}
?>

<?php include('../model/footer.php'); ?>
