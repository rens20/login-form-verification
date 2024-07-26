<?php
// Include necessary files and start session
session_start();
include('../config/conn.php');
include('../model/header.php');
include('../model/sidebar.php');

// Handle the success message
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<div class="container mx-auto p-6 bg-white shadow-md rounded-lg max-w-3xl mx-auto mt-10">
    <?php if ($message): ?>
        <div class="p-4 mb-4 bg-green-100 text-green-800 rounded">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <!-- Rest of your content for communities page -->
    <h1 class="text-2xl font-bold mb-4 text-center">My Communities</h1>
    <!-- Your existing code for displaying created and joined communities -->
</div>

<?php include('../model/footer.php'); ?>
