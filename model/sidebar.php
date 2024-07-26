<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .sidebar ul li {
            position: relative;
            color:white;
        }
        .dropdown-menu {
            display: none;
            position: relative;
            left: 0;
            right: 0;
            color:white;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }
        .dropdown-menu li a {
            color: black;
        }
        .dropdown-menu.visible {
            display: block;
        }

        
         /* If it's not turning white, force it. */
        .dropdown-menu li a {
        color: white !important; 
    }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white p-4">
    <div class="flex justify-center">
    </div>
    <?php

   
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        $stmt = $conn->prepare("SELECT * FROM `tbl_user` WHERE tbl_user_id = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $username = $user['username'];
            echo "
            <h2 class='text-2xl font-semibold text-center' id='username-$userId'>$username
            <span id='sidebar-toggle' class='text-white text-3xl cursor-pointer'>&gt;</span>
            </h2>";
        } else {
            echo "<p class='text-red-500'>User not found.</p>";
        }
    } else {
        echo "<p class='text-red-500'>You are not logged in.</p>";
    }
    ?>

    <ul class="mt-6">
        <li>
            <a href="../public/user-page.php" class="block px-4 py-2 mt-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-home text-white"></i> Home
            </a>
        </li>
        <li>
            <a href="../public/user-profile.php" class="block px-4 py-2 mt-2 hover:bg-gray-700">
                <i class="fas fa-user"></i> Profile
            </a>
        </li>
        <li>
            <a href="../public/user-post.php" class="block px-4 py-2 mt-2 hover:bg-gray-700">
                <i class="fas fa-edit"></i> Post
            </a>
        </li>
        <li>
            <a href="../public/user-message.php" class="block px-4 py-2 mt-2 hover:bg-gray-700">
                <i class="fas fa-envelope"></i> Messages
            </a>
        </li>
        <li>
            <a href="../public/user-notification.php" class="block px-4 py-2 mt-2 hover:bg-gray-700">
                <i class="fas fa-envelope"></i> Notification
            </a>
        </li>
        <li class="relative">
            <a href="#" onclick="toggleDropdown(event, 'settings-dropdown')" class="block px-4 py-2 mt-2 hover:bg-gray-700 flex items-center">
                <i class="fas fa-cog"> </i>  Settings
                <i class="fas fa-chevron-down ml-auto"></i>
            </a>
            <ul id="settings-dropdown" class="dropdown-menu">
           <li>
    <a href="#" onclick="editProfilePopup()" class="block px-4 py-2 text-white flex items-center">
        <i class="fas fa-user-edit mr-2"></i>
        Edit Profile
    </a>
</li>

          
            </ul>
        </li>
   <li class="relative">
    <a href="#" onclick="toggleDropdown(event, 'community-dropdown')" class="block px-4 py-2 mt-2 hover:bg-gray-700 flex items-center">
        <i class="fas fa-users"></i> Community
        <i class="fas fa-chevron-down ml-auto"></i>
    </a>
    <ul id="community-dropdown" class="dropdown-menu hidden">
        <li>
            <a href="#" onclick="createCommunity()" class="block px-4 py-2 text-white flex items-center">
                <i class="fas fa-plus mr-2"></i> Create Community
            </a>
        </li>
        <li>
           <a href="../public/my-communities.php" onclick="fetchMyCommunities(); return false;" class="block px-4 py-2 text-white flex items-center">
                <i class="fas fa-list mr-2"></i> My Communities
            </a>
            <ul id="my-communities" class="dropdown-menu hidden">
                <!-- Community items will be dynamically inserted here -->
            </ul>
        </li>
    </ul>
</li>


        <li>
            <a href="../public/user-logout.php" class="block px-4 py-2 mt-2 hover:bg-gray-700">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            
        </li>
    </ul>
</div>

<script src="../javascript/model-function.js"></script>
</body>
</html>
