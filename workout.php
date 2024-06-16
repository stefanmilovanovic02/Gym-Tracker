<?php
include 'php/konekcija.php';
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['korisnik_id'];

// Fetch user's profile details
$sql_user = "SELECT username, height, sex, starting_weight, current_weight, goal_weight
             FROM users
             WHERE id = ?";

$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param('i', $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

// Fetch user's nutrition goals
$sql_nutrition = "SELECT calories, protein, carbs, fats, creatine, water
                 FROM nutrition
                 WHERE user_id = ?
                 ORDER BY date DESC
                 LIMIT 1";

$stmt_nutrition = $conn->prepare($sql_nutrition);
$stmt_nutrition->bind_param('i', $user_id);
$stmt_nutrition->execute();
$result_nutrition = $stmt_nutrition->get_result();

// Check if nutrition goals data exists
if ($result_nutrition->num_rows > 0) {
    $nutrition = $result_nutrition->fetch_assoc();
} else {
    // Initialize $nutrition array with null values if no data found
    $nutrition = [
        'calories' => null,
        'protein' => null,
        'carbs' => null,
        'fats' => null,
        'creatine' => null,
        'water' => null
    ];
}

$stmt_user->close();
$stmt_nutrition->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Workou</title>
    

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
