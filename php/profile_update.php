<?php
include 'konekcija.php'; // Include database connection

session_start();

// Check if user is logged in
if (!isset($_SESSION['korisnik_id'])) {     
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['korisnik_id'];

// Handle profile details update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Profile Details
    $height = $_POST['height'];
    $sex = $_POST['sex'];
    $starting_weight = $_POST['startedWeight'];
    $current_weight = $_POST['currentWeight'];
    $goal_weight = $_POST['goalWeight'];

    // Update the users table
    $sql = "UPDATE users SET 
                height = ?, 
                sex = ?, 
                starting_weight = ?, 
                current_weight = ?, 
                goal_weight = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issddi', $height, $sex, $starting_weight, $current_weight, $goal_weight, $user_id);
    $stmt->execute();

    // Redirect back to profile page after update
    header("Location: profile.php");
    exit();
}
?>
