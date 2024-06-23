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

    // Fetch exercises for the selected workout from JSON in the workouts table
    $sql = "SELECT exercises FROM workouts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $workout_id);
    $stmt->execute();
    $stmt->bind_result($exercises_json);
    $stmt->fetch();
    $stmt->close();

    $exercises = json_decode($exercises_json, true);

    // Fetch exercise names
    $placeholders = implode(',', array_fill(0, count($exercises), '?'));
    $sql_exercises = "SELECT id, name FROM exercises WHERE id IN ($placeholders)";
    $stmt_exercises = $conn->prepare($sql_exercises);
    $stmt_exercises->bind_param(str_repeat('i', count($exercises)), ...$exercises);
    $stmt_exercises->execute();
    $result_exercises = $stmt_exercises->get_result();

    $exercise_list = [];
    while ($row = $result_exercises->fetch_assoc()) {
        $exercise_list[] = $row;
    }

    $stmt_exercises->close();
    $conn->close();

    echo json_encode($exercise_list);
}
?>
