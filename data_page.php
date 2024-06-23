<?php
include 'php/konekcija.php';
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['korisnik_id'];

// Fetch goals from user profile
$sql_goals = "SELECT calories, protein, carbs, fats, creatine, water FROM nutrition WHERE user_id = ? ORDER BY date DESC LIMIT 1";
$stmt_goals = $conn->prepare($sql_goals);
$stmt_goals->bind_param("i", $user_id);
$stmt_goals->execute();
$result_goals = $stmt_goals->get_result();
$goals = $result_goals->fetch_assoc();
$stmt_goals->close();

// Initialize arrays to store fetched data
$nutrition_data = [];

// Fetch nutrition data
$nutrition_query = "SELECT date, calories, protein, carbs, fats, creatine, water FROM nutrition_log WHERE user_id = ?";
$stmt_nutrition = $conn->prepare($nutrition_query);
$stmt_nutrition->bind_param("i", $user_id);
$stmt_nutrition->execute();
$result_nutrition = $stmt_nutrition->get_result();
while ($row = $result_nutrition->fetch_assoc()) {
    $nutrition_data[] = $row;
}
$stmt_nutrition->close();

$conn->close();

// Encode PHP arrays to JSON for JavaScript consumption
$nutrition_json = json_encode($nutrition_data);
$goals_json = json_encode($goals);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <title>Tracker</title>
    <style>
        body {
            background-color: #1c1c1c;
            color: white;
        }
        .navbar, .card {
            background-color: #333;
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
        .progress-bar {
            line-height: 20px;
            height: 30px;
        }
        .progress-title {
            margin-bottom: 5px;
            font-weight: bold;
        }
        .progress-container {
            margin-bottom: 20px;
        }
        #date-display {
            cursor: pointer;
            font-weight: bold;
            font-size: 1.2em;
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
                <a class="nav-link" href="workouts.php">Workouts</a>
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

<div class="container">
    <div class="text-center my-4">
        <button class="btn btn-secondary" onclick="changeDate(-1)"><i class="fas fa-chevron-left"></i></button>
        <span id="date-display" onclick="showDatePicker()">Today</span>
        <button class="btn btn-secondary" onclick="changeDate(1)"><i class="fas fa-chevron-right"></i></button>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="progress-container">
                <div class="progress-title">Calories</div>
                <div class="progress">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 0%" id="calories-progress">0%</div>
                </div>
            </div>
            <div class="progress-container">
                <div class="progress-title">Protein</div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 0%" id="protein-progress">0%</div>
                </div>
            </div>
            <div class="progress-container">
                <div class="progress-title">Carbohydrates</div>
                <div class="progress">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 0%" id="carbs-progress">0%</</div>
                </div>
            </div>
            <div class="progress-container">
                <div class="progress-title">Fat</div>
                <div class="progress">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 0%" id="fats-progress">0%</div>
                </div>
            </div>
            <div class="progress-container">
                <div class="progress-title">Creatine</div>
                <div class="progress">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" id="creatine-progress">0%</div>
                </div>
            </div>
            <div class="progress-container">
                <div class="progress-title">Water</div>
                <div class="progress">
                    <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%" id="water-progress">0%</div>
                </div>
            </div>
            <div class="progress-container">
                <div class="progress-title">Exercise</div>
                <div class="progress">
                    <div class="progress-bar bg-dark" role="progressbar" style="width: 0%" id="exercise-progress">0%</div>
                </div>
            </div>
        </div>
    </div>

    <div class="line-chart">
        <h5 class="text-center">Line Chart</h5>
        <canvas id="lineChart"></canvas>
    </div>
</div>

<!-- Include Chart.js and datepicker libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.en.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    loadProgressBars();
    loadLineChart();
    $('#date-display').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    }).on('changeDate', function(e) {
        document.getElementById('date-display').innerText = e.format();
        loadProgressBars();
        loadLineChart();
    });
});

function loadProgressBars() {
    let nutritionData = <?php echo $nutrition_json; ?>;
    let goals = <?php echo $goals_json; ?>;
    let selectedDate = document.getElementById('date-display').innerText;
    let todayData = nutritionData.find(data => data.date === selectedDate);

    if (todayData) {
        updateProgressBar('calories', todayData.calories, goals.calories);
        updateProgressBar('protein', todayData.protein, goals.protein);
        updateProgressBar('carbs', todayData.carbs, goals.carbs);
        updateProgressBar('fats', todayData.fats, goals.fats);
        updateProgressBar('creatine', todayData.creatine, goals.creatine);
        updateProgressBar('water', todayData.water, goals.water);
    } else {
        resetProgressBars();
    }

    // Check if exercise log exists for the selected date
    // This part is a placeholder and should be replaced with actual exercise log check
    let exerciseLogExists = true;  // Replace this with actual check
    if (exerciseLogExists) {
        updateProgressBar('exercise', 100, 100);
    } else {
        updateProgressBar('exercise', 0, 100);
    }
}

function updateProgressBar(id, value, goal) {
    let percentage = (value / goal) * 100;
    let progressBar = document.getElementById(id + '-progress');
    progressBar.style.width = percentage + '%';
    progressBar.innerText = Math.round(percentage) + '%';
}

function resetProgressBars() {
    let ids = ['calories', 'protein', 'carbs', 'fats', 'creatine', 'water', 'exercise'];
    ids.forEach(id => {
        let progressBar = document.getElementById(id + '-progress');
        progressBar.style.width = '0%';
        progressBar.innerText = '0%';
    });
}

function loadLineChart() {
    let ctx = document.getElementById('lineChart').getContext('2d');
    let nutritionData = <?php echo $nutrition_json; ?>;
    let selectedDate = document.getElementById('date-display').innerText;
    let labels = nutritionData.map(data => data.date);
    let caloriesData = nutritionData.map(data => data.calories);
    let proteinData = nutritionData.map(data => data.protein);
    let carbsData = nutritionData.map(data => data.carbs);
    let fatsData = nutritionData.map(data => data.fats);
    let creatineData = nutritionData.map(data => data.creatine);
    let waterData = nutritionData.map(data => data.water);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                { label: 'Calories', data: caloriesData, borderColor: 'rgba(75, 192, 192, 1)', fill: false },
                { label: 'Protein', data: proteinData, borderColor: 'rgba(54, 162, 235, 1)', fill: false },
                { label: 'Carbohydrates', data: carbsData, borderColor: 'rgba(255, 206, 86, 1)', fill: false },
                { label: 'Fats', data: fatsData, borderColor: 'rgba(255, 99, 132, 1)', fill: false },
                { label: 'Creatine', data: creatineData, borderColor: 'rgba(153, 102, 255, 1)', fill: false },
                { label: 'Water', data: waterData, borderColor: 'rgba(255, 159, 64, 1)', fill: false }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: { display: true, title: { display: true, text: 'Date' } },
                y: { display: true, title: { display: true, text: 'Amount' } }
            }
        }
    });
}

function changeDate(direction) {
    let currentDate = new Date(document.getElementById('date-display').innerText);
    currentDate.setDate(currentDate.getDate() + direction);
    document.getElementById('date-display').innerText = currentDate.toISOString().split('T')[0];
    loadProgressBars();
    loadLineChart();
}

function showDatePicker() {
    $('#date-display').datepicker('show');
}
</script>
</body>
</html>
