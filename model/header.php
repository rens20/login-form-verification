<?php
include('../config/conn.php'); 
// include("../controller/login.php");
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Header</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-lg font-semibold">
                <a href="index.php">Home</a>
            </div>
            <div class="relative">
                <?php if (isset($_SESSION['username'])): ?>
                    <h1 class="mr-4 text-white text-sm"><?php echo ($_SESSION['username']);
                   ?></h>
                   
                    <button id="user-menu-button" class="bg-gray-700 p-2 rounded hover:bg-gray-600">Menu</button>
                    <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white text-gray-900 border border-gray-200 rounded shadow-lg hidden">
                        <a href="profile.php" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                        <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100">Logout</a>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="mr-4">Login</a>
                    <a href="register.php" class="mr-4">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <script>
        document.getElementById('user-menu-button').addEventListener('click', function () {
            var menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
