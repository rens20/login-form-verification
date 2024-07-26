<?php
session_start();
include('../config/conn.php');
include('../model/header.php');
include('../model/sidebar.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view your communities.";
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch all communities created by the user
$sqlCreated = "SELECT id, name, description, created_by, created_at, image FROM communities WHERE created_by = :userId";
$stmtCreated = $conn->prepare($sqlCreated);
$stmtCreated->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtCreated->execute();
$createdCommunities = $stmtCreated->fetchAll(PDO::FETCH_ASSOC);

// Fetch all communities the user has joined
$sqlJoined = "SELECT c.id, c.name, c.description, c.created_by, c.created_at, c.image
              FROM communities c
              JOIN community_memberships cm ON c.id = cm.community_id
              WHERE cm.user_id = :userId";
$stmtJoined = $conn->prepare($sqlJoined);
$stmtJoined->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtJoined->execute();
$joinedCommunities = $stmtJoined->fetchAll(PDO::FETCH_ASSOC);

// Fetch all communities that the user has not joined
$sqlNotJoined = "SELECT id, name, description, created_by, created_at, image
                 FROM communities
                 WHERE id NOT IN (
                     SELECT community_id FROM community_memberships WHERE user_id = :userId
                 )";
$stmtNotJoined = $conn->prepare($sqlNotJoined);
$stmtNotJoined->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtNotJoined->execute();
$notJoinedCommunities = $stmtNotJoined->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mx-auto p-6 bg-white shadow-md rounded-lg max-w-3xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-4 text-center">My Communities</h1>
    
    <h2 class="text-xl font-semibold mb-4">Communities I've Created</h2>
    <ul>
        <?php if (!empty($createdCommunities)): ?>
            <?php foreach ($createdCommunities as $community): ?>
                <li class="mb-2">
                    <div class="p-4 bg-gray-100 rounded">
                        <h3 class="text-lg font-bold"><?= htmlspecialchars($community['name']) ?></h3>
                        <p><?= htmlspecialchars($community['description']) ?></p>
                        <p><strong>Created At:</strong> <?= htmlspecialchars($community['created_at']) ?></p>
                        <?php if ($community['image']): ?>
                            <img src="../../uploads/<?= htmlspecialchars($community['image']) ?>" alt="Community Image" class="w-full h-auto mt-2">
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No communities created yet.</p>
        <?php endif; ?>
    </ul>

    <h2 class="text-xl font-semibold mb-4">Communities I've Joined</h2>
    <ul>
        <?php if (!empty($joinedCommunities)): ?>
            <?php foreach ($joinedCommunities as $community): ?>
                <li class="mb-2">
                    <div class="p-4 bg-gray-100 rounded">
                        <h3 class="text-lg font-bold"><?= htmlspecialchars($community['name']) ?></h3>
                        <p><?= htmlspecialchars($community['description']) ?></p>
                        <p><strong>Created By:</strong> <?= htmlspecialchars($community['created_by']) ?></p>
                        <p><strong>Created At:</strong> <?= htmlspecialchars($community['created_at']) ?></p>
                        <?php if ($community['image']): ?>
                            <img src="../../uploads/<?= htmlspecialchars($community['image']) ?>" alt="Community Image" class="w-full h-auto mt-2">
                        <?php endif; ?>
                        <form action="./libs/join-leave-community.php" method="post" class="mt-2">
                            <input type="hidden" name="community_id" value="<?= htmlspecialchars($community['id']) ?>">
                            <button type="submit" name="action" value="leave" class="bg-red-500 text-white px-4 py-2 rounded">Leave Community</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have not joined any communities yet.</p>
        <?php endif; ?>
    </ul>

    <h2 class="text-xl font-semibold mb-4">Communities I Can Join</h2>
    <ul>
        <?php if (!empty($notJoinedCommunities)): ?>
            <?php foreach ($notJoinedCommunities as $community): ?>
                <li class="mb-2">
                    <div class="p-4 bg-gray-100 rounded">
                        <h3 class="text-lg font-bold"><?= htmlspecialchars($community['name']) ?></h3>
                        <p><?= htmlspecialchars($community['description']) ?></p>
                        <p><strong>Created By:</strong> <?= htmlspecialchars($community['created_by']) ?></p>
                        <p><strong>Created At:</strong> <?= htmlspecialchars($community['created_at']) ?></p>
                        <?php if ($community['image']): ?>
                            <img src="../../uploads/<?= htmlspecialchars($community['image']) ?>" alt="Community Image" class="w-full h-auto mt-2">
                        <?php endif; ?>
                        <form action="./libs/join-leave-community.php" method="post" class="mt-2">
                            <input type="hidden" name="community_id" value="<?= htmlspecialchars($community['id']) ?>">
                            <button type="submit" name="action" value="join" class="bg-blue-500 text-white px-4 py-2 rounded">Join Community</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have joined all available communities.</p>
        <?php endif; ?>
    </ul>
</div>

<?php include('../model/footer.php'); ?>
