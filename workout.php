<?php
include 'php/konekcija.php';
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['korisnik_id'];

// Fetch workouts
$sql_workouts = "SELECT id, name FROM workouts WHERE user_id = ?";
$stmt_workouts = $conn->prepare($sql_workouts);
$stmt_workouts->bind_param('i', $user_id);
$stmt_workouts->execute();
$result_workouts = $stmt_workouts->get_result();

$workouts = [];
while ($row = $result_workouts->fetch_assoc()) {
    $workouts[] = $row;
}

$stmt_workouts->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Workouts</title>
    <style>
        body {
            background-color: #1c1c1c;
            color: white;
        }
        .navbar, .card {
            background-color: #333;
        }
        .workout-card {
            background-color: #444;
            margin-bottom: 20px;
        }
        .workout-card h5 {
            margin-bottom: 10px;
        }
        .delete-link {
            color: red;
            float: right;
        }
        .add-workout-btn {
            background-color: #0056b3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .add-workout-btn:hover {
            background-color: #004080;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="data_page.php">Logo</a>
    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="add.php">Add Today</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="charts.php">Charts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="workouts.php">Workouts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="php/logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <?php if (empty($workouts)): ?>
        <div class="alert alert-warning text-center">No workouts yet</div>
    <?php else: ?>
        <?php foreach ($workouts as $workout): ?>
            <div class="card workout-card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($workout['name']); ?>
                        <a href="delete_workout.php?id=<?php echo $workout['id']; ?>" class="delete-link">delete</a>
                    </h5>
                    <p class="card-text">
                        <?php
                        $sql_exercises = "SELECT e.name FROM exercise_logs el JOIN exercises e ON el.exercise_id = e.id WHERE el.workout_id = ?";
                        $stmt_exercises = $conn->prepare($sql_exercises);
                        $stmt_exercises->bind_param('i', $workout['id']);
                        $stmt_exercises->execute();
                        $result_exercises = $stmt_exercises->get_result();
                        $exercises = [];
                        while ($row = $result_exercises->fetch_assoc()) {
                            $exercises[] = $row['name'];
                        }
                        $stmt_exercises->close();
                        echo htmlspecialchars(implode(', ', $exercises));
                        ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <button class="add-workout-btn" onclick="window.location.href='add_workout.php'">Add Workout</button>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
