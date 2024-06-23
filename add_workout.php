<?php
include 'php/konekcija.php';
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['korisnik_id'];

// Fetch exercises
$sql_exercises = "SELECT id, name FROM exercises";
$stmt_exercises = $conn->prepare($sql_exercises);
$stmt_exercises->execute();
$result_exercises = $stmt_exercises->get_result();

$exercises = [];
while ($row = $result_exercises->fetch_assoc()) {
    $exercises[] = $row;
}

$stmt_exercises->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Add Workout</title>
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
        .search-input {
            width: 100%;
            margin-bottom: 20px;
        }
        .exercise-list {
            max-height: 300px;
            overflow-y: auto;
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
    <div class="card workout-card">
        <div class="card-body">
            <h5 class="card-title">Add Workout</h5>
            <form action="php/save_workout.php" method="post">
                <div class="form-group">
                    <label for="name">Workout Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="search">Search Exercises</label>
                    <input type="text" class="form-control search-input" id="search" onkeyup="searchExercises()" placeholder="Search for exercises...">
                </div>
                <div class="form-group exercise-list">
                    <?php foreach ($exercises as $exercise): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="exercises[]" value="<?php echo $exercise['id']; ?>" id="exercise<?php echo $exercise['id']; ?>">
                            <label class="form-check-label" for="exercise<?php echo $exercise['id']; ?>">
                                <?php echo htmlspecialchars($exercise['name']); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="btn add-workout-btn">Save Workout</button>
            </form>
        </div>
    </div>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
function searchExercises() {
    let input = document.getElementById('search').value.toLowerCase();
    let exerciseList = document.querySelectorAll('.exercise-list .form-check');
    
    exerciseList.forEach(function(exercise) {
        let exerciseName = exercise.querySelector('label').innerText.toLowerCase();
        if (exerciseName.includes(input)) {
            exercise.style.display = '';
        } else {
            exercise.style.display = 'none';
        }
    });
}
</script>
</body>
</html>