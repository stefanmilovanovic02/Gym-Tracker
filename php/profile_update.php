<?php
include 'konekcija.php'; // Include database connection

session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['korisnik_id'];

// Get user input from form
$height = $_POST['height'];
$sex = $_POST['sex'];
$starting_weight = $_POST['startedWeight'];
$current_weight = $_POST['currentWeight'];
$goal_weight = $_POST['goalWeight'];
$calories = $_POST['calories'];
$protein = $_POST['protein'];
$carbs = $_POST['carbs'];
$fats = $_POST['fats'];
$creatine = $_POST['creatine'];
$water = $_POST['water'];

// Update users table
$sql_update_user = "UPDATE users SET height = ?, sex = ?, starting_weight = ?, current_weight = ?, goal_weight = ? WHERE id = ?";
$stmt_update_user = $conn->prepare($sql_update_user);
$stmt_update_user->bind_param('sssssi', $height, $sex, $starting_weight, $current_weight, $goal_weight, $user_id);
$stmt_update_user->execute();

// Insert or update nutrition goals
$sql_nutrition_exists = "SELECT id FROM nutrition WHERE user_id = ?";
$stmt_nutrition_exists = $conn->prepare($sql_nutrition_exists);
$stmt_nutrition_exists->bind_param('i', $user_id);
$stmt_nutrition_exists->execute();
$result_nutrition_exists = $stmt_nutrition_exists->get_result();

if ($result_nutrition_exists->num_rows > 0) {
    // Update existing nutrition goals
    $sql_update_nutrition = "UPDATE nutrition SET calories = ?, protein = ?, carbs = ?, fat = ?, creatine = ?, water = ?, date = NOW() WHERE user_id = ?";
    $stmt_update_nutrition = $conn->prepare($sql_update_nutrition);
    $stmt_update_nutrition->bind_param('iiiiisi', $calories, $protein, $carbs, $fats, $creatine, $water, $user_id);
} else {
    // Insert new nutrition goals
    $sql_insert_nutrition = "INSERT INTO nutrition (user_id, date, calories, protein, carbs, fat, creatine, water) VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)";
    $stmt_update_nutrition = $conn->prepare($sql_insert_nutrition);
    $stmt_update_nutrition->bind_param('iiiiiii', $user_id, $calories, $protein, $carbs, $fats, $creatine, $water);
}

$stmt_update_nutrition->execute();

$stmt_update_user->close();
$stmt_update_nutrition->close();
$stmt_nutrition_exists->close();
$conn->close();

// Redirect back to profile page
header("Location: profile.php");
exit();
?>
