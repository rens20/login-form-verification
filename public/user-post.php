<?php
session_start();
include('../config/conn.php');
include('../model/header.php');
include('../model/sidebar.php');

// Fetch user communities
$userId = $_SESSION['user_id'];

$sqlCreated = "SELECT id, name FROM communities WHERE created_by = :userId";
$stmtCreated = $conn->prepare($sqlCreated);
$stmtCreated->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtCreated->execute();
$createdCommunities = $stmtCreated->fetchAll(PDO::FETCH_ASSOC);

$sqlJoined = "SELECT c.id, c.name FROM communities c
              JOIN community_memberships cm ON c.id = cm.community_id
              WHERE cm.user_id = :userId";
$stmtJoined = $conn->prepare($sqlJoined);
$stmtJoined->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtJoined->execute();
$joinedCommunities = $stmtJoined->fetchAll(PDO::FETCH_ASSOC);

// Combine created and joined communities
$communities = array_merge($createdCommunities, $joinedCommunities);
?>

<div class="container mx-auto p-6 bg-white shadow-md rounded-lg max-w-3xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-4 text-center">Create Post</h1>

    <form id="postForm" action="./functions/post-process.php" method="post" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
            <input type="text" id="title" name="title" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div>
            <label for="text" class="block text-sm font-medium text-gray-700">Text:</label>
            <textarea id="text" name="text" rows="4" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
        </div>

        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Image (optional):</label>
            <input type="file" id="image" name="image" accept="image/*"
                class="mt-1 block w-full text-gray-500 file:py-2 file:px-4 file:border file:border-gray-300 file:rounded-md file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
        </div>

        <div>
            <label for="community" class="block text-sm font-medium text-gray-700">Select Community:</label>
            <select id="community" name="community_id" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Select a Community</option>
                <?php foreach ($communities as $community): ?>
                    <option value="<?= htmlspecialchars($community['id']) ?>"><?= htmlspecialchars($community['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="w-1/2 px-4 py-2 bg-black text-white font-semibold rounded-md shadow-sm">Post</button>
        </div>
    </form>
</div>

<?php include('../model/footer.php'); ?>
