<?php
session_start();

include 'functions/user_functions.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $userDetails = getUserDetails($userId, $conn);

    if ($userDetails) {
        echo "<h1>Welcome, " . $userDetails['name'] . "</h1>";
        echo "<p>Email: " . $userDetails['email'] . "</p>";
        echo "<p>Contact #: " . $userDetails['contact'] . "</p>";
        // Add other user details you want to display
    } else {
        echo "User not found.";
    }
} else {
    echo "You are not logged in.";
}
?>