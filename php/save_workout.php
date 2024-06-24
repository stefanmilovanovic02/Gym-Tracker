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
    $exercises = isset($_POST['exercises']) ? $_POST['exercises'] : [];

    // Convert exercises array to JSON
    $exercises_json = json_encode($exercises);

    // Insert new workout
    $sql_insert_workout = "INSERT INTO workouts (user_id, name, exercises) VALUES (?, ?, ?)";
    $stmt_insert_workout = $conn->prepare($sql_insert_workout);
    $stmt_insert_workout->bind_param('iss', $user_id, $name, $exercises_json);
    $stmt_insert_workout->execute();
    $stmt_insert_workout->close();

    $conn->close();

    header("Location: ../workouts.php");
    exit();
}
?>
