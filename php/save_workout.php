<?php
include 'konekcija.php';
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['korisnik_id'];
    $name = $_POST['name'];
    $exercises = $_POST['exercises'];

    // Insert new workout
    $sql_insert_workout = "INSERT INTO workouts (user_id, name) VALUES (?, ?)";
    $stmt_insert_workout = $conn->prepare($sql_insert_workout);
    $stmt_insert_workout->bind_param('is', $user_id, $name);
    $stmt_insert_workout->execute();
    $workout_id = $stmt_insert_workout->insert_id;
    $stmt_insert_workout->close();

    // Insert exercises for the workout
    $sql_insert_exercise_log = "INSERT INTO exercise_logs (workout_id, exercise_id) VALUES (?, ?)";
    $stmt_insert_exercise_log = $conn->prepare($sql_insert_exercise_log);
    foreach ($exercises as $exercise_id) {
        $stmt_insert_exercise_log->bind_param('ii', $workout_id, $exercise_id);
        $stmt_insert_exercise_log->execute();
    }
    $stmt_insert_exercise_log->close();

    $conn->close();

    header("Location: ../workouts.php");
    exit();
}
?>
