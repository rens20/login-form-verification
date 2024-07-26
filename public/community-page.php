<?php
session_start();
include('../config/conn.php');
include('../model/header.php');
include('../model/sidebar.php');
?>

    <title>Communities</title>
    <div class="container mx-auto p-4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold mb-4">My Communities</h1>
            <?php
            // Include the script to fetch and display communities
            include('../public/libs/fetch-communities.php');
            ?>
        </div>
    </div>
 
    <?php include('../model/footer.php'); ?>

