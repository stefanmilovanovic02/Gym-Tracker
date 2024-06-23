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
    $calories = $_POST['calories'];
    $protein = $_POST['protein'];
    $carbs = $_POST['carbs'];
    $fat = $_POST['fat'];
    $creatine = $_POST['creatine'];
    $water = $_POST['water'];

    // Insert nutrition log
    $sql_insert_nutrition = "INSERT INTO nutrition_log (user_id, date, calories, protein, carbs, fat, creatine, water) VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)";
    $stmt_insert_nutrition = $conn->prepare($sql_insert_nutrition);
    $stmt_insert_nutrition->bind_param('iiiiiii', $user_id, $calories, $protein, $carbs, $fat, $creatine, $water);
    $stmt_insert_nutrition->execute();
    $stmt_insert_nutrition->close();

    $conn->close();

    header("Location: ../add.php");
    exit();
}
?>
