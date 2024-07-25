<?php
session_start();
include('../../config/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $query = $_GET['query'];

    $stmt = $conn->prepare("SELECT id, name, description FROM communities WHERE name LIKE ?");
    $like_query = "%$query%";
    $stmt->bind_param("s", $like_query);
    $stmt->execute();
    $result = $stmt->get_result();

    $communities = [];
    while ($row = $result->fetch_assoc()) {
        $communities[] = $row;
    }

    echo json_encode($communities);
    
    $stmt->close();
    $conn->close();
}
?>
