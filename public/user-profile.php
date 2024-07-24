<?php
session_start();
include('../config/conn.php');
include('../model/header.php');
include('../model/sidebar.php');

if (!isset($_SESSION['user_id'])) {
    echo "<div class='flex items-center justify-center min-h-screen bg-gray-100'><p class='text-red-500'>You are not logged in.</p></div>";
    exit;
}

$loggedInUserId = $_SESSION['user_id'];
$profileUserId = isset($_GET['user_id']) ? intval($_GET['user_id']) : $loggedInUserId;

// Fetch profile user details
$sql = "SELECT tbl_user_id, first_name, last_name, profile_image FROM tbl_user WHERE tbl_user_id = :tbl_user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':tbl_user_id', $profileUserId, PDO::PARAM_INT);
$stmt->execute();
$userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userDetails) {
    echo "<div class='flex items-center justify-center min-h-screen bg-gray-100'><p class='text-red-500'>User not found.</p></div>";
    exit;
}

// Check if user has a profile image in the database
$profileImage = !empty($userDetails['profile_image']) ? '../uploads/' . htmlspecialchars($userDetails['profile_image']) : '../uploads/default.png';

// Check if the logged-in user follows the profile user
$sql = "SELECT COUNT(*) as is_following FROM followers WHERE follower_id = :follower_id AND following_id = :following_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':follower_id', $loggedInUserId, PDO::PARAM_INT);
$stmt->bindParam(':following_id', $profileUserId, PDO::PARAM_INT);
$stmt->execute();
$isFollowing = $stmt->fetchColumn() > 0;

// Fetch followers and following counts
$sql = "SELECT (SELECT COUNT(*) FROM followers WHERE following_id = :user_id) as followers_count,
               (SELECT COUNT(*) FROM followers WHERE follower_id = :user_id) as following_count";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $profileUserId, PDO::PARAM_INT);
$stmt->execute();
$counts = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="flex flex-col items-center">
            <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Picture" class="w-24 h-24 rounded-full mb-4">
            <h1 class="text-2xl font-bold mb-4 text-center"><?php echo htmlspecialchars($userDetails['first_name']) . " " . htmlspecialchars($userDetails['last_name']); ?></h1>
            
            <!-- for folloing and followers -->
            <div class="text-center">
    <div class="flex justify-center space-x-8">
        <div>
            <p class="text-lg font-semibold">Followers: <?php echo $counts['followers_count']; ?></p> 
        </div>
        <div>
            <p class="text-lg font-semibold">Following: <?php echo $counts['following_count']; ?></p>
        </div>
    </div>
</div>



            <div class="flex space-x-4 mb-4">
                <?php if ($profileUserId !== $loggedInUserId): ?>
                    <button id="follow-btn" class="bg-black text-white px-4 py-2 rounded">
                        <?php echo $isFollowing ? 'Message' : 'Follow'; ?>
                    </button>
                <?php endif; ?>
            </div>
            
           
        </div>
    </div>
</div>

<script>
    document.getElementById('follow-btn').addEventListener('click', function() {
    var button = this;
    var isFollowing = button.textContent.trim() === 'Message';
    var action = isFollowing ? 'unfollow' : 'follow';
    
    fetch('./libs/user-follow.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            action: action,
            profile_user_id: <?php echo json_encode($profileUserId); ?>,
            logged_in_user_id: <?php echo json_encode($loggedInUserId); ?>
        })
    }).then(response => response.json()).then(data => {
        if (data.success) {
            if (action === 'follow') {
                button.textContent = 'Message';
            } else {
                button.textContent = 'Follow';
            }
            // Update follower and following counts
            document.querySelector('.text-lg.font-semibold').textContent = 'Followers: ' + data.followers_count;
            document.querySelectorAll('.text-lg.font-semibold')[1].textContent = 'Following: ' + data.following_count;
        } else {
            alert('Something went wrong.');
        }
    });
});
    function toggleEditForm() {
        var form = document.getElementById('edit-form');
        form.classList.toggle('hidden');
    }
</script>

<?php include('../model/footer.php'); ?>

