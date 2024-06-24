<?php
include 'konekcija.php';
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['korisnik_id'];
$date = $_POST['date'];
$calories = $_POST['calories'];
$protein = $_POST['protein'];
$carbs = $_POST['carbs'];
$fats = $_POST['fat'];
$creatine = $_POST['creatine'];
$water = $_POST['water'];

// Check if a nutrition log already exists for today
$sql_check = "SELECT * FROM nutrition_log WHERE user_id = ? AND date = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param('is', $user_id, $date);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // Update existing log
    $sql_update = "UPDATE nutrition_log SET calories = ?, protein = ?, carbs = ?, fats = ?, creatine = ?, water = ? WHERE user_id = ? AND date = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param('iiiiiiis', $calories, $protein, $carbs, $fats, $creatine, $water, $user_id, $date);
    $stmt_update->execute();
    $stmt_update->close();
} else {
    // Insert new log
    $sql_insert = "INSERT INTO nutrition_log (user_id, date, calories, protein, carbs, fats, creatine, water) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param('isiiiiii', $user_id, $date, $calories, $protein, $carbs, $fats, $creatine, $water);
    $stmt_insert->execute();
    $stmt_insert->close();
}

$conn->close();

header("Location: ../add.php");
exit();
?>
