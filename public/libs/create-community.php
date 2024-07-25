<?php
session_start();
include('../../config/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $created_by = $_SESSION['user_id'];

    try {
        $stmt = $conn->prepare("INSERT INTO communities (name, description, created_by) VALUES (:name, :description, :created_by)");
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':created_by', $created_by, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Community created successfully!";
        } else {
            echo "Error: " . implode(" ", $stmt->errorInfo());
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
