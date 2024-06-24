<?php
include 'konekcija.php';
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['korisnik_id'];
$workout_id = $_POST['workout'];
$date = date('Y-m-d');

foreach ($_POST as $key => $value) {
    if (strpos($key, 'weight_') === 0) {
        $exercise_id = str_replace('weight_', '', $key);
        foreach ($value as $index => $weight) {
            $reps = $_POST['reps_' . $exercise_id][$index];
            $sets = $index + 1; // Set number is the index + 1

            // Convert values to variables
            $weight_val = $weight;
            $reps_val = $reps;

            // Insert exercise log
            $sql_insert_exercise_log = "INSERT INTO exercise_logs (user_id, workout_id, exercise_id, sets, reps, weight, date) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert_exercise_log = $conn->prepare($sql_insert_exercise_log);
            $stmt_insert_exercise_log->bind_param('iiiidss', $user_id, $workout_id, $exercise_id, $sets, $reps_val, $weight_val, $date);
            $stmt_insert_exercise_log->execute();
            $stmt_insert_exercise_log->close();
        }
    }
}

$conn->close();

header("Location: ../add.php");
exit();
?>
