<?php
include 'konekcija.php'; // Include database connection

session_start();

if (!isset($_SESSION['korisnik_id'])) {     
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['korisnik_id'];

// Handle nutrition goals update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nutrition Goals
    $calories = $_POST['calories'];
    $protein = $_POST['protein'];
    $carbs = $_POST['carbs'];
    $fats = $_POST['fat'];
    $creatine = $_POST['creatine'];
    $water = $_POST['water'];

    // Check if there's already an entry for today
    $sql_check = "SELECT id FROM nutrition WHERE user_id = :user_id AND date = CURDATE()";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':user_id' => $user_id]);
    $existing_nutrition = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($existing_nutrition) {
        // Update existing entry for today
        $sql_update = "UPDATE nutrition SET 
                        calories = :calories,
                        protein = :protein,
                        carbs = :carbs,
                        fats = :fats,
                        creatine = :creatine,
                        water = :water
                      WHERE id = :nutrition_id";

        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            ':calories' => $calories,
            ':protein' => $protein,
            ':carbs' => $carbs,
            ':fats' => $fats,
            ':creatine' => $creatine,
            ':water' => $water,
            ':nutrition_id' => $existing_nutrition['id']
        ]);
    } else {
        // Insert new entry for today
        $sql_insert = "INSERT INTO nutrition (user_id, date, calories, protein, carbs, fats, creatine, water)
                       VALUES (:user_id, CURDATE(), :calories, :protein, :carbs, :fats, :creatine, :water)";

        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([
            ':user_id' => $user_id,
            ':calories' => $calories,
            ':protein' => $protein,
            ':carbs' => $carbs,
            ':fats' => $fats,
            ':creatine' => $creatine,
            ':water' => $water
        ]);
    }

    // Redirect back to profile page after update
    header("Location: profile.php");
    exit();
}
?>
