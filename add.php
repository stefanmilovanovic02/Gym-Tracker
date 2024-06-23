<?php
    // Uključivanje konekcije na bazu podataka
    include 'php/konekcija.php';

    // Pokretanje sesije
    session_start();

    // Provera da li je korisnik ulogovan
    if (!isset($_SESSION['korisnik_id'])) {     
        // Korisnik nije ulogovan, redirekcija na stranicu za login
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Add Today</title>
</head>
<body>

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

<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="data_page.php">Logo</a>
    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            <li class="nav-item">
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
    <!-- Card 1: Add Micronutrients -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Add Micronutrients</h5>
            <form>
                <div class="form-group">
                    <label for="calories">Calories</label>
                    <input type="number" class="form-control" id="calories" placeholder="Enter calories (kcal)">
                </div>
                <div class="form-group">
                    <label for="protein">Protein</label>
                    <input type="number" class="form-control" id="protein" placeholder="Enter protein (g)">
                </div>
                <div class="form-group">
                    <label for="carbs">Carbohydrates</label>
                    <input type="number" class="form-control" id="carbs" placeholder="Enter carbohydrates (g)">
                </div>
                <div class="form-group">
                    <label for="fat">Fat</label>
                    <input type="number" class="form-control" id="fat" placeholder="Enter fat (g)">
                </div>
                <div class="form-group">
                    <label for="creatine">Creatine</label>
                    <input type="number" class="form-control" id="creatine" placeholder="Enter creatine (g)">
                </div>
                <div class="form-group">
                    <label for="water">Water</label>
                    <input type="number" class="form-control" id="water" placeholder="Enter water (ml)">
                </div>
            </form>
        </div>
    </div>

    <!-- Card 2: Add Workout Routine -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Add Workout Routine</h5>
            <form>
                <div class="form-group">
                    <label for="exercise">Exercise</label>
                    <input type="text" class="form-control" id="exercise" placeholder="Enter exercise name">
                </div>
                <div class="form-group">
                    <label for="weight">Weight</label>
                    <input type="number" class="form-control" id="weight" placeholder="Enter weight (kg)">
                </div>
                <div class="form-group">
                    <label for="sets">Sets</label>
                    <input type="number" class="form-control" id="sets" placeholder="Enter number of sets">
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>