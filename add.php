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
    <title>Add Today</title>
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
        .set-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .set-group input {
            width: 30%;
        }
        .add-set-btn {
            margin-top: 10px;
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
    <!-- Card 1: Add Micronutrients -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Add Micronutrients</h5>
            <form action="php/save_nutrition.php" method="post">
                <div class="form-group">
                    <label for="calories">Calories</label>
                    <input type="number" class="form-control" id="calories" name="calories" placeholder="Enter calories (kcal)" required>
                </div>
                <div class="form-group">
                    <label for="protein">Protein</label>
                    <input type="number" class="form-control" id="protein" name="protein" placeholder="Enter protein (g)" required>
                </div>
                <div class="form-group">
                    <label for="carbs">Carbohydrates</label>
                    <input type="number" class="form-control" id="carbs" name="carbs" placeholder="Enter carbohydrates (g)" required>
                </div>
                <div class="form-group">
                    <label for="fat">Fat</label>
                    <input type="number" class="form-control" id="fat" name="fat" placeholder="Enter fat (g)" required>
                </div>
                <div class="form-group">
                    <label for="creatine">Creatine</label>
                    <input type="number" class="form-control" id="creatine" name="creatine" placeholder="Enter creatine (g)" required>
                </div>
                <div class="form-group">
                    <label for="water">Water</label>
                    <input type="number" class="form-control" id="water" name="water" placeholder="Enter water (ml)" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Nutrition</button>
            </form>
        </div>
    </div>

    <!-- Card 2: Add Workout Routine -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Add Workout Routine</h5>
            <form action="php/save_workout_log.php" method="post" id="workout-form">
                <div class="form-group">
                    <label for="workout">Select Workout</label>
                    <select class="form-control" id="workout" name="workout" required>
                        <option value="">Select workout</option>
                        <?php foreach ($workouts as $workout): ?>
                            <option value="<?php echo $workout['id']; ?>"><?php echo htmlspecialchars($workout['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="exercise-list">
                    <!-- Exercises will be loaded here with JavaScript -->
                </div>
                <button type="submit" class="btn btn-primary">Log Workout</button>
            </form>
        </div>
    </div>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('workout').addEventListener('change', function() {
    let workoutId = this.value;
    if (workoutId) {
        fetchExercises(workoutId);
    } else {
        document.getElementById('exercise-list').innerHTML = '';
    }
});

function fetchExercises(workoutId) {
    fetch('php/fetch_exercises.php?workout_id=' + workoutId)
        .then(response => response.json())
        .then(data => {
            let exerciseList = document.getElementById('exercise-list');
            exerciseList.innerHTML = '';
            data.forEach(exercise => {
                let exerciseCard = document.createElement('div');
                exerciseCard.className = 'card';
                exerciseCard.innerHTML = `
                    <div class="card-body">
                        <h5 class="card-title">${exercise.name}</h5>
                        <div class="set-group">
                            <label>Set 1</label>
                            <input type="number" class="form-control" name="weight_${exercise.id}[]" placeholder="Weight (kg)">
                            <input type="number" class="form-control" name="reps_${exercise.id}[]" placeholder="Reps">
                        </div>
                        <button type="button" class="btn btn-secondary add-set-btn" onclick="addSet(this, ${exercise.id})">Add set</button>
                    </div>
                `;
                exerciseList.appendChild(exerciseCard);
            });
        })
        .catch(error => console.error('Error fetching exercises:', error));
}

function addSet(button, exerciseId) {
    let setGroups = button.parentNode.querySelectorAll('.set-group').length + 1;
    let setGroup = document.createElement('div');
    setGroup.className = 'set-group';
    setGroup.innerHTML = `
        <label>Set ${setGroups}</label>
        <input type="number" class="form-control" name="weight_${exerciseId}[]" placeholder="Weight (kg)">
        <input type="number" class="form-control" name="reps_${exerciseId}[]" placeholder="Reps">
    `;
    button.parentNode.insertBefore(setGroup, button);
}
</script>
</body>
</html>
