<?php
include 'konekcija.php';
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['workout_id'])) {
    $workout_id = $_GET['workout_id'];

    // Fetch exercises for the selected workout
    $sql_exercises = "SELECT e.id, e.name FROM exercise_logs el JOIN exercises e ON el.exercise_id = e.id WHERE el.workout_id = ?";
    $stmt_exercises = $conn->prepare($sql_exercises);
    $stmt_exercises->bind_param('i', $workout_id);
    $stmt_exercises->execute();
    $result_exercises = $stmt_exercises->get_result();

    $exercises = [];
    while ($row = $result_exercises->fetch_assoc()) {
        $exercises[] = $row;
    }

    $stmt_exercises->close();
    $conn->close();

    echo json_encode($exercises);
}
?>
