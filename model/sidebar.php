
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white p-4 sidebar sidebar-hidden">
    <div class="flex justify-center">
       
    </div>
             <?php 
                
                    $stmt = $conn->prepare("SELECT * FROM `tbl_user`");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    foreach ($result as $row) {
                        $username = $row['username'];
                        
                    }

                    ?>

                    <tr>     
    <h2 class="text-2xl font-semibold text-center " id="username-<?= $userID ?>"><?php echo $username ?>
  <span id="sidebar-toggle" class="text-white text-3xl cursor-pointer">&gt;</span>
    </h2>

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
        <a href="../public/user-settings.php" class="block px-4 py-2 mt-2 hover:bg-gray-700">
            <i class="fas fa-cog"></i> Settings
        </a>
    </li>
    <li>
        <a href="../public/user-community.php" class="block px-4 py-2 mt-2 hover:bg-gray-700">
            <i class="fas fa-users"></i> Community
        </a>
    </li>
    <li>
        <a href="../public/user-logout.php" class="block px-4 py-2 mt-2 hover:bg-gray-700">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </li>
</ul>
</ul>

</div>
<script>
        document.getElementById('sidebar-toggle').addEventListener('click', function () {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('sidebar-hidden');
            sidebar.classList.toggle('sidebar-visible');
            var content = document.querySelector('.main-content');
            content.classList.toggle('ml-64');
            this.textContent = this.textContent === '>' ? '<' : '>';
        });
    </script>

</body>
</html>
