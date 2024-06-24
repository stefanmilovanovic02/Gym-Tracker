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
$result_exercises = $conn->query($sql_exercises);

$exercises = [];
while ($row = $result_exercises->fetch_assoc()) {
    $exercises[] = $row;
}
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
                <a class="nav-link" href="data_page.php">Home</a>
            </li>
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
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Add Workout</h5>
            <form action="php/save_workout.php" method="post">
                <div class="form-group">
                    <label for="workout-name">Workout Name</label>
                    <input type="text" class="form-control" id="workout-name" name="name" placeholder="Enter workout name" required>
                </div>
                <div class="form-group">
                    <label for="exercise-search">Search Exercise</label>
                    <input type="text" class="form-control" id="exercise-search" placeholder="Search exercises">
                </div>
                <div class="form-group">
                    <label>Exercises</label>
                    <div id="exercise-list" style="max-height: 200px; overflow-y: scroll;">
                        <?php foreach ($exercises as $exercise): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="exercises[]" value="<?php echo $exercise['id']; ?>" id="exercise_<?php echo $exercise['id']; ?>">
                                <label class="form-check-label" for="exercise_<?php echo $exercise['id']; ?>">
                                    <?php echo htmlspecialchars($exercise['name']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save Workout</button>
            </form>
        </div>
    </div>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('exercise-search').addEventListener('input', function() {
    let filter = this.value.toLowerCase();
    let exercises = document.querySelectorAll('#exercise-list .form-check');
    exercises.forEach(function(exercise) {
        let text = exercise.textContent.toLowerCase();
        if (text.includes(filter)) {
            exercise.style.display = '';
        } else {
            exercise.style.display = 'none';
        }
    });
});
</script>
</body>
</html>
