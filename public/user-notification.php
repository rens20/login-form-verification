<?php
session_start();
include('../config/conn.php');
include('../model/header.php');
include('../model/sidebar.php');

if (!isset($_SESSION['user_id'])) {
    echo "<div class='flex items-center justify-center min-h-screen bg-gray-100'><p class='text-red-500'>You are not logged in.</p></div>";
    exit;
}

$userId = $_SESSION['user_id'];

$sql = "SELECT n.id, u.first_name, u.last_name, n.created_at
        FROM notifications n
        JOIN tbl_user u ON n.follower_id = u.tbl_user_id
        WHERE n.user_id = :user_id AND n.notification_type = 'follow'
        ORDER BY n.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT); // Ensure proper binding
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);





?>

<div class="container mx-auto p-6 bg-white shadow-md rounded-lg max-w-3xl">
    <h1 class="text-2xl font-bold mb-4 text-center">Notifications</h1>
    <?php if (count($notifications) > 0): ?>
        
        <ul>
            <?php foreach ($notifications as $notification): ?>
                <li class="mb-4 p-4 border border-gray-300 rounded-md shadow-sm">
                    <p><strong><?php echo htmlspecialchars($notification['first_name']) . " " . htmlspecialchars($notification['last_name']); ?></strong> started following you on <?php echo htmlspecialchars($notification['created_at']); ?>.</p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-center">You have no new notifications.</p>
    <?php endif; ?>

</div>

<?php include('../model/footer.php'); ?>
