<?php
include 'konekcija.php';
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $workout_id = $_GET['id'];
    $user_id = $_SESSION['korisnik_id'];

    // Delete workout
    $sql_delete = "DELETE FROM workouts WHERE id = ? AND user_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param('ii', $workout_id, $user_id);
    $stmt_delete->execute();
    $stmt_delete->close();
}

$conn->close();

header("Location: ../workouts.php");
exit();
?>
