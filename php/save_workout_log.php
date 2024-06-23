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
    $workout_id = $_POST['workout'];

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'weight_') === 0) {
            $exercise_id = str_replace('weight_', '', $key);
            $weight = $value;
            $sets = $_POST['sets_' . $exercise_id];
            $reps = $_POST['reps_' . $exercise_id];

            // Insert exercise log
            $sql_insert_exercise_log = "INSERT INTO exercise_logs (user_id, workout_id, exercise_id, sets, reps, weight, date) VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt_insert_exercise_log = $conn->prepare($sql_insert_exercise_log);
            $stmt_insert_exercise_log->bind_param('iiiidi', $user_id, $workout_id, $exercise_id, $sets, $reps, $weight);
            $stmt_insert_exercise_log->execute();
            $stmt_insert_exercise_log->close();
        }
    }

    $conn->close();

    header("Location: ../add.php");
    exit();
}
?>
