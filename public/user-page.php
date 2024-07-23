<?php
session_start();
include('../config/conn.php');
include('../model/header.php');
include('../model/sidebar.php');

// Fetch posts
$sql = "SELECT * FROM posts";
$stmt = $conn->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mx-auto p-6 bg-white shadow-md rounded-lg max-w-3xl">
    <h1 class="text-2xl font-bold mb-4 text-center">Posts</h1>

    <?php foreach ($posts as $post): ?>
        <div class="mb-6 p-4 border border-gray-300 rounded-md shadow-sm">
            <h2 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($post['title']); ?></h2>
            <p class="text-gray-700 mb-2"><?php echo nl2br(htmlspecialchars($post['text'])); ?></p>
            <?php if (!empty($post['image'])): ?>
                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" class="mb-2 w-full h-auto">
            <?php endif; ?>
            <div class="flex items-center space-x-4">
                <form action="libs/like-process.php" method="post">
                    <input type="hidden" name="postId" value="<?php echo $post['id']; ?>" />
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-sm">Like</button>
                </form>
                <span>Likes: <?php echo isset($post['like_count']) ? $post['like_count'] : 0; ?></span>
                <button onclick="toggleComments(<?php echo $post['id']; ?>)" class="px-4 py-2 bg-gray-600 text-white font-semibold rounded-md shadow-sm">View Comments</button>
            </div>

            <!-- Comments Section -->
            <div id="comments-<?php echo $post['id']; ?>" class="mt-4 hidden">
                <h3 class="text-lg font-medium mb-2">Comments</h3>
                <?php
                $sql = "SELECT c.comment, u.username FROM comments c JOIN tbl_user u ON c.user_id = u.tbl_user_id WHERE c.post_id = :post_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':post_id', $post['id']);
                $stmt->execute();
                $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="mb-2">
                        <p class="text-sm"><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['comment']); ?></p>
                    </div>
                <?php endforeach; ?>

                <form action="libs/comment-process.php" method="post" class="mt-2">
                    <input type="hidden" name="postId" value="<?php echo $post['id']; ?>" />
                    <div>
                        <textarea name="comment" rows="2" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Add a comment..."></textarea>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-sm">Comment</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
function toggleComments(postId) {
    var commentsSection = document.getElementById('comments-' + postId);
    if (commentsSection.classList.contains('hidden')) {
        commentsSection.classList.remove('hidden');
    } else {
        commentsSection.classList.add('hidden');
    }
}
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.like-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            var postId = this.getAttribute('data-post-id');
            var likeCountElement = document.getElementById('like-count-' + postId);

            fetch('libs/like-process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ postId: postId }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    likeCountElement.textContent = data.likeCount;
                } else {
                    alert(data.message);
                }
            });
        });
    });

    document.querySelectorAll('.comment-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            var postId = this.getAttribute('data-post-id');
            var commentSection = document.getElementById('comment-section-' + postId);
            commentSection.classList.toggle('hidden');
        });
    });

    document.querySelectorAll('.submit-comment').forEach(function (button) {
        button.addEventListener('click', function () {
            var postId = this.getAttribute('data-post-id');
            var commentTextarea = document.querySelector('#comment-section-' + postId + ' .comment-textarea');

            fetch('libs/comment-process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ postId: postId, comment: commentTextarea.value }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    var commentsList = document.getElementById('comments-list-' + postId);
                    var commentElement = document.createElement('div');
                    commentElement.textContent = data.comment;
                    commentsList.appendChild(commentElement);
                    commentTextarea.value = '';
                } else {
                    alert(data.message);
                }
            });
        });
    });
});
</script>

<?php include('../model/footer.php'); ?>
