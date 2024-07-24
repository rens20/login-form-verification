<?php
session_start();
include('../config/conn.php');
include('../model/header.php');
include('../model/sidebar.php');
// Fetch posts with like and view counts
$sql = "
    SELECT p.id, p.title, p.text, p.image, p.tbl_user_id, 
           COALESCE(likeCounts.likeCount, 0) as like_count,
           COALESCE(viewCounts.viewCount, 0) as views
    FROM posts p
    LEFT JOIN (
        SELECT post_id, COUNT(*) as likeCount 
        FROM likes 
        GROUP BY post_id
    ) likeCounts ON p.id = likeCounts.post_id
    LEFT JOIN (
        SELECT post_id, COUNT(*) as viewCount
        FROM post_views
        GROUP BY post_id
    ) viewCounts ON p.id = viewCounts.post_id
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<style>
    .like-btn {
        transition: transform 0.2s ease, font-size 0.2s ease;
        font-size: 1.9rem;
    }

    .like-btn:hover {
        transform: scale(1.1);
        font-size: 1.2rem;
        color: black;
    }

    .view-btn {
        transition: transform 0.2s ease;
    }
</style>

<div class="container mx-auto p-6 bg-white shadow-md rounded-lg max-w-3xl">
    <h1 class="text-2xl font-bold mb-4 text-center">Posts</h1>

    <?php foreach ($posts as $post): ?>
        <div class="mb-6 p-4 border border-gray-300 rounded-md shadow-sm post" data-post-id="<?php echo $post['id']; ?>">
            <h2 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($post['title']); ?></h2>
            <p class="text-gray-700 mb-2"><?php echo nl2br(htmlspecialchars($post['text'])); ?></p>
            <?php if (!empty($post['image'])): ?>
                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" class="mb-2 w-full h-auto">
            <?php endif; ?>
            <div class="flex items-center space-x-4">
                <button class="like-btn text-blue-600" data-post-id="<?php echo $post['id']; ?>">
                    <i class="fas fa-thumbs-up"></i>
                </button>
                <span id="like-count-<?php echo $post['id']; ?>"><?php echo isset($post['like_count']) ? $post['like_count'] : 0; ?></span>
                <button onclick="toggleComments(<?php echo $post['id']; ?>)" class="px-4 py-2 bg-black text-white font-semibold rounded-md shadow-sm">Comments</button>
               <span id="view-count-<?php echo $post['id']; ?>">
    <i class="fas fa-eye"></i> <?php echo isset($post['views']) ? $post['views'] : 0; ?>
</span>

            </div>

            <!-- Comments Section -->
            <div id="comments-<?php echo $post['id']; ?>" class="mt-4 hidden">
                <h3 class="text-lg font-medium mb-2">Comments</h3>
                <div id="comments-list-<?php echo $post['id']; ?>">
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
                </div>

                <form class="comment-form mt-2" data-post-id="<?php echo $post['id']; ?>">
                    <input type="hidden" name="postId" value="<?php echo $post['id']; ?>" />
                    <div>
                        <textarea name="comment" rows="2" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Add a comment..."></textarea>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="px-4 py-2 bg-black text-white font-semibold rounded-md shadow-sm">Comment</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    function toggleComments(postId) {
    var commentsSection = document.getElementById('comments-' + postId);
    commentsSection.classList.toggle('hidden');
}
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
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });

    document.querySelectorAll('.comment-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            var postId = this.getAttribute('data-post-id');
            var formData = new FormData(this);

            fetch('libs/comment-process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    var commentsList = document.getElementById('comments-list-' + postId);
                    var commentElement = document.createElement('div');
                    commentElement.className = 'mb-2';
                    commentElement.innerHTML = `<p class="text-sm"><strong>${data.username}:</strong> ${data.comment}</p>`;
                    commentsList.appendChild(commentElement);
                    this.reset();
                } else {
                    alert(data.message);
                }
            });
        });
    });

    // Track post views
    document.querySelectorAll('.post').forEach(function (postElement) {
        var postId = postElement.getAttribute('data-post-id');
        fetch('libs/view-process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ postId: postId }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                var viewCountElement = document.getElementById('view-count-' + postId);
                viewCountElement.textContent = data.viewCount;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});

</script>

<?php include('../model/footer.php'); ?>
