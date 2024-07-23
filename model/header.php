

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Header</title>
     <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }
        .sidebar-hidden {
            transform: translateX(-100%);
        }
    </style>
</head>
<body>

    <header class="bg-gray-800 text-white p-4 w-full">
        <div class="container mx-auto flex justify-between items-center">
            <button id="burger-menu" class="text-white text-2xl">&#9776;</button>
            <div class="text-lg font-semibold">
                
            </div>
            <div class="relative">
                <?php if (isset($_SESSION['username'])): ?>
                   <img src="../../images/profile-user.png" id="user-menu-button" class=" p-1 rounded-full hover:bg-gray-600 w-10 h-10 object-cover">
                    <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white text-gray-900 border border-gray-200 rounded shadow-lg hidden">
                    <?php if (isset($_SESSION['username'])): ?>
                    <a href="#" id="edit-profile" class="block px-4 py-2 hover:bg-gray-100"><?php echo $_SESSION['username']; ?></a>
                <?php endif; ?>
            
                        <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100">Logout</a>
                    </div>
                <?php else: ?>
            
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Modal for updating profile -->
    <!-- <div id="profile-modal" class="modal">
        <div class="modal-content">
            <h2 class="text-lg font-semibold mb-4">Update Profile</h2>
            <form id="profile-form" action="update-profile.php" method="post" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="new-name" class="block text-gray-700">New Name:</label>
                    <input type="text" id="new-name" name="new-name" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div class="mb-4">
                    <label for="new-photo" class="block text-gray-700">New Profile Photo:</label>
                    <input type="file" id="new-photo" name="new-photo" accept="image/*" class="w-full">
                </div>
                <div class="flex justify-end">
                    <button type="button" id="close-modal" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div> -->
    </header>
  


