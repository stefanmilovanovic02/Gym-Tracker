<?php
include 'php/konekcija.php';
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['korisnik_id'];

// Initialize arrays to store fetched data
$nutrition_data = [];
$weights_data = [];
$workouts_data = [];

// Fetch nutrition data
$nutrition_query = "SELECT date, calories, protein, carbs, fats, creatine, water FROM nutrition WHERE user_id = ?";
$stmt_nutrition = $conn->prepare($nutrition_query);
$stmt_nutrition->bind_param("i", $user_id);
$stmt_nutrition->execute();
$result_nutrition = $stmt_nutrition->get_result();
while ($row = $result_nutrition->fetch_assoc()) {
    $nutrition_data[] = $row;
}
$stmt_nutrition->close();

// Fetch weights data
$weights_query = "SELECT date, weight FROM weights WHERE user_id = ?";
$stmt_weights = $conn->prepare($weights_query);
$stmt_weights->bind_param("i", $user_id);
$stmt_weights->execute();
$result_weights = $stmt_weights->get_result();
while ($row = $result_weights->fetch_assoc()) {
    $weights_data[] = $row;
}
$stmt_weights->close();

// Fetch workouts data
$workouts_query = "SELECT date, duration FROM workouts WHERE user_id = ?";
$stmt_workouts = $conn->prepare($workouts_query);
$stmt_workouts->bind_param("i", $user_id);
$stmt_workouts->execute();
$result_workouts = $stmt_workouts->get_result();
while ($row = $result_workouts->fetch_assoc()) {
    $workouts_data[] = $row;
}
$stmt_workouts->close();

$conn->close();

// Encode PHP arrays to JSON for JavaScript consumption
$nutrition_json = json_encode($nutrition_data);
$workouts_json = json_encode($workouts_data);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Tracker</title>
</head>
<body>

<style>
    body {
        background-color: #1c1c1c;
        color: white;
    }
    .navbar, .card {
        background-color: #333;
    }
    .progress {
        height: 20px;
    }
    .progress-bar {
        line-height: 20px;
    }
    .container {
        margin-top: 20px;
    }
    .line-chart {
        background-color: white;
        color: black;
        padding: 20px;
        border-radius: 8px;
    }
</style>

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
                <a class="nav-link" href="index.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="text-center my-4">
        <button class="btn btn-secondary" onclick="changeDate(-1)">&lt; Previous</button>
        <span id="date-display">Today</span>
        <button class="btn btn-secondary" onclick="changeDate(1)">Next &gt;</button>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Calories</h5>
            <div class="progress mb-2">
                <div class="progress-bar bg-info" role="progressbar" style="width: 0%" id="calories-progress">0%</div>
            </div>
            <h5 class="card-title">Macronutrients</h5>
            <div class="progress mb-2">
                <div class="progress-bar bg-success" role="progressbar" style="width: 0%" id="protein-progress">0%</div>
            </div>
            <div class="progress mb-2">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 0%" id="carbs-progress">0%</div>
            </div>
            <div class="progress mb-2">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 0%" id="fat-progress">0%</div>
            </div>
            <h5 class="card-title">Other</h5>
            <div class="progress mb-2">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" id="creatine-progress">0%</div>
            </div>
            <div class="progress mb-2">
                <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%" id="water-progress">0%</div>
            </div>
            <div class="progress mb-2">
                <div class="progress-bar bg-dark" role="progressbar" style="width: 0%" id="exercise-progress">0%</div>
            </div>
        </div>
    </div>

    <div class="line-chart" onclick="redirectToChartPage()">
        <h5 class="text-center">Line Chart</h5>
        <canvas id="lineChart"></canvas>
    </div>
</div>

<script src="script.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
