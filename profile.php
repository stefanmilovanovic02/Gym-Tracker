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
        'fat' => null,
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
    <title>Profile</title>
    <style>
        body {
            background-color: #333;
            color: #fff;
        }
        .navbar, .card {
            background-color: #444;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="data_page.php">Logo</a>
    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            <li class="nav-item">
            <li class="nav-item">
                <a class="nav-link" href="data_page.php">Home</a>
            </li>
                <a class="nav-link" href="add.php">Add Today</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="workout.php">Workouts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="charts.php">Charts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="php/logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <!-- Profile Details Card -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Profile Details</h5>
            <form action="php/profile_update.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="height">Height (cm)</label>
                    <input type="number" class="form-control" id="height" name="height" value="<?php echo htmlspecialchars($user['height']); ?>">
                </div>
                <div class="form-group">
                    <label for="sex">Sex</label>
                    <select class="form-control" id="sex" name="sex">
                        <option value="Male" <?php if ($user['sex'] === 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($user['sex'] === 'Female') echo 'selected'; ?>>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="startedWeight">Started Weight (kg)</label>
                    <input type="number" class="form-control" id="startedWeight" name="startedWeight" value="<?php echo htmlspecialchars($user['starting_weight']); ?>">
                </div>
                <div class="form-group">
                    <label for="currentWeight">Current Weight (kg)</label>
                    <input type="number" class="form-control" id="currentWeight" name="currentWeight" value="<?php echo htmlspecialchars($user['current_weight']); ?>">
                </div>
                <div class="form-group">
                    <label for="goalWeight">Goal Weight (kg)</label>
                    <input type="number" class="form-control" id="goalWeight" name="goalWeight" value="<?php echo htmlspecialchars($user['goal_weight']); ?>">
                </div>

                <!-- Nutrition Goals Card -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Nutrition Goals</h5>
                        <div class="form-group">
                            <label for="calories">Calories</label>
                            <input type="number" class="form-control" id="calories" name="calories" value="<?php echo htmlspecialchars($nutrition['calories']); ?>" placeholder="Enter calories (kcal)">
                        </div>
                        <div class="form-group">
                            <label for="protein">Protein</label>
                            <input type="number" class="form-control" id="protein" name="protein" value="<?php echo htmlspecialchars($nutrition['protein']); ?>" placeholder="Enter protein (g)">
                        </div>
                        <div class="form-group">
                            <label for="carbs">Carbohydrates</label>
                            <input type="number" class="form-control" id="carbs" name="carbs" value="<?php echo htmlspecialchars($nutrition['carbs']); ?>" placeholder="Enter carbs (g)">
                        </div>
                        <div class="form-group">
                            <label for="fats">Fats</label>
                            <input type="number" class="form-control" id="fats" name="fats" value="<?php echo htmlspecialchars($nutrition['fats']); ?>" placeholder="Enter fats (g)">
                        </div>
                        <div class="form-group">
                            <label for="creatine">Creatine</label>
                            <input type="number" class="form-control" id="creatine" name="creatine" value="<?php echo htmlspecialchars($nutrition['creatine']); ?>" placeholder="Enter creatine (g)">
                        </div>
                        <div class="form-group">
                            <label for="water">Water</label>
                            <input type="number" class="form-control" id="water" name="water" value="<?php echo htmlspecialchars($nutrition['water']); ?>" placeholder="Enter water (ml)">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
