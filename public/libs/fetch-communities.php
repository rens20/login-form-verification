<?php

// include('../../config/conn.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '<p>User not logged in.</p>';
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT id, name, image FROM communities WHERE created_by = :user_id");
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $communities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($communities)) {
        echo '<p>No communities found.</p>';
    } else {
        echo '<ul>';
        foreach ($communities as $community) {
            echo '<li>';
            echo '<a href="../public/community-page.php?id=' . htmlspecialchars($community['id']) . '">';
            echo '<img src="../../uploads/' . htmlspecialchars($community['image']) . '" alt="' . htmlspecialchars($community['name']) . '" style="width: 30px; height: 30px; margin-right: 5px;">';
            echo htmlspecialchars($community['name']);
            echo '</a>';
            echo '</li>';
        }
        echo '</ul>';
    }
} catch (PDOException $e) {
    echo '<p>Database error: ' . htmlspecialchars($e->getMessage()) . '</p>';


}
?>
