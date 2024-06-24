<?php
include 'php/konekcija.php'; // Include database connection

session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['korisnik_id'])) {     
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['korisnik_id'];

// Fetch user's weight logs for the chart
$sql_weight = "SELECT date, weight FROM weights WHERE user_id = ? ORDER BY date ASC";
$stmt_weight = $conn->prepare($sql_weight);
$stmt_weight->bind_param('i', $user_id);
$stmt_weight->execute();
$result_weight = $stmt_weight->get_result();
$weight_logs = [];
while ($row = $result_weight->fetch_assoc()) {
    $weight_logs[] = $row;
}
$stmt_weight->close();

// Fetch user's nutrition logs for the chart
$sql_nutrition_log = "SELECT date, calories, protein, carbs, fats, creatine, water FROM nutrition_log WHERE user_id = ? ORDER BY date ASC";
$stmt_nutrition_log = $conn->prepare($sql_nutrition_log);
$stmt_nutrition_log->bind_param('i', $user_id);
$stmt_nutrition_log->execute();
$result_nutrition_log = $stmt_nutrition_log->get_result();
$nutrition_logs = [];
while ($row = $result_nutrition_log->fetch_assoc()) {
    $nutrition_logs[] = $row;
}
$stmt_nutrition_log->close();

// Fetch user's exercise logs for the chart
$sql_exercise_logs = "SELECT el.date, e.name, el.sets, el.reps, el.weight FROM exercise_logs el JOIN exercises e ON el.exercise_id = e.id WHERE el.user_id = ? ORDER BY el.date ASC";
$stmt_exercise_logs = $conn->prepare($sql_exercise_logs);
$stmt_exercise_logs->bind_param('i', $user_id);
$stmt_exercise_logs->execute();
$result_exercise_logs = $stmt_exercise_logs->get_result();
$exercise_logs = [];
while ($row = $result_exercise_logs->fetch_assoc()) {
    $exercise_logs[] = $row;
}
$stmt_exercise_logs->close();

// Fetch nutrition goals
$sql_goals = "SELECT calories, protein, carbs, fats, creatine, water FROM nutrition WHERE user_id = ? ORDER BY date DESC LIMIT 1";
$stmt_goals = $conn->prepare($sql_goals);
$stmt_goals->bind_param('i', $user_id);
$stmt_goals->execute();
$result_goals = $stmt_goals->get_result();
$goals = $result_goals->fetch_assoc();
$stmt_goals->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Charts</title>
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
        .dropdown-menu {
            background-color: #555;
            color: #fff;
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
    <div class="d-flex justify-content-between mb-3">
        <div class="btn-group">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Select Chart
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#" onclick="selectChart('weight')">Weight</a>
                <a class="dropdown-item" href="#" onclick="selectChart('calories')">Calories</a>
                <a class="dropdown-item" href="#" onclick="selectChart('protein')">Protein</a>
                <a class="dropdown-item" href="#" onclick="selectChart('carbs')">Carbs</a>
                <a class="dropdown-item" href="#" onclick="selectChart('fats')">Fats</a>
                <a class="dropdown-item" href="#" onclick="selectChart('creatine')">Creatine</a>
                <a class="dropdown-item" href="#" onclick="selectChart('water')">Water</a>
            </div>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Select Exercise
            </button>
            <div class="dropdown-menu">
                <?php foreach ($exercise_logs as $log): ?>
                    <a class="dropdown-item" href="#" onclick="selectChart('exercise', '<?php echo $log['name']; ?>')"><?php echo $log['name']; ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Select Period
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#" onclick="selectPeriod('1week')">1 Week</a>
                <a class="dropdown-item" href="#" onclick="selectPeriod('1month')">1 Month</a>
                <a class="dropdown-item" href="#" onclick="selectPeriod('3months')">3 Months</a>
                <a class="dropdown-item" href="#" onclick="selectPeriod('6months')">6 Months</a>
                <a class="dropdown-item" href="#" onclick="selectPeriod('1year')">1 Year</a>
                <a class="dropdown-item" href="#" onclick="selectPeriod('alltime')">All Time</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body text-center">
            <div id="overallValues">
                <div class="row">
                    <div class="col">
                        <h5>Start</h5>
                        <p id="startValue">0</p>
                    </div>
                    <div class="col">
                        <h5>Current</h5>
                        <p id="currentValue">0</p>
                    </div>
                    <div class="col">
                        <h5>Change</h5>
                        <p id="changeValue">0</p>
                    </div>
                    <div class="col">
                        <h5 id="averageLabel">Average</h5>
                        <p id="averageValue">0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chartType = 'weight';
    let period = 'alltime';
    let myChart;
    const goals = <?php echo json_encode($goals); ?>;

    function selectChart(type, exerciseName = '') {
        chartType = type;
        updateChart(exerciseName);
    }

    function selectPeriod(p) {
        period = p;
        updateChart();
    }

    function filterDataByPeriod(data) {
        let endDate = new Date();
        let startDate;
        switch (period) {
            case '1week':
                startDate = new Date();
                startDate.setDate(endDate.getDate() - 7);
                break;
            case '1month':
                startDate = new Date();
                startDate.setMonth(endDate.getMonth() - 1);
                break;
            case '3months':
                startDate = new Date();
                startDate.setMonth(endDate.getMonth() - 3);
                break;
            case '6months':
                startDate = new Date();
                startDate.setMonth(endDate.getMonth() - 6);
                break;
            case '1year':
                startDate = new Date();
                startDate.setFullYear(endDate.getFullYear() - 1);
                break;
            case 'alltime':
                startDate = new Date(0);
                break;
        }

        return data.filter(log => {
            let logDate = new Date(log.date);
            return logDate >= startDate && logDate <= endDate;
        });
    }

    function updateChart(exerciseName = '') {
        if (myChart) {
            myChart.destroy();
        }

        let weight_logs = <?php echo json_encode($weight_logs); ?>;
        let nutrition_logs = <?php echo json_encode($nutrition_logs); ?>;
        let exercise_logs = <?php echo json_encode($exercise_logs); ?>;

        let labels, data, datasetLabel, goalValue;

        switch (chartType) {
            case 'weight':
                weight_logs = filterDataByPeriod(weight_logs);
                labels = weight_logs.map(log => log.date);
                data = weight_logs.map(log => log.weight);
                datasetLabel = 'Weight Progress';
                goalValue = 100; // Example goal, you can set this from the database
                document.getElementById('startValue').innerText = data[0] + ' kg';
                document.getElementById('currentValue').innerText = data[data.length - 1] + ' kg';
                document.getElementById('changeValue').innerText = (data[data.length - 1] - data[0]) + ' kg';
                document.getElementById('averageLabel').innerText = '';
                document.getElementById('averageValue').innerText = '';
                break;
            case 'calories':
            case 'protein':
            case 'carbs':
            case 'fats':
            case 'creatine':
            case 'water':
                nutrition_logs = filterDataByPeriod(nutrition_logs);
                labels = nutrition_logs.map(log => log.date);
                data = nutrition_logs.map(log => log[chartType]);
                datasetLabel = chartType.charAt(0).toUpperCase() + chartType.slice(1) + ' Intake';
                goalValue = goals[chartType];
                document.getElementById('startValue').innerText = data[0];
                document.getElementById('currentValue').innerText = data[data.length - 1];
                document.getElementById('changeValue').innerText = (data[data.length - 1] - data[0]);
                document.getElementById('averageLabel').innerText = 'Average Daily Intake';
                document.getElementById('averageValue').innerText = (data.reduce((a, b) => a + b, 0) / data.length).toFixed(2);
                break;
            case 'exercise':
                exercise_logs = exercise_logs.filter(log => log.name === exerciseName);
                exercise_logs = filterDataByPeriod(exercise_logs);
                labels = exercise_logs.map(log => log.date);
                data = exercise_logs.map(log => log.weight); // Ovo može da se promeni u zavisnosti od toga šta želiš da prikazuješ
                datasetLabel = exerciseName + ' Progress';
                goalValue = 100; // Example goal, you can set this from the database
                document.getElementById('startValue').innerText = data[0];
                document.getElementById('currentValue').innerText = data[data.length - 1];
                document.getElementById('changeValue').innerText = (data[data.length - 1] - data[0]);
                document.getElementById('averageLabel').innerText = 'Average Weight';
                document.getElementById('averageValue').innerText = (data.reduce((a, b) => a + b, 0) / data.length).toFixed(2);
                break;
        }

        const ctx = document.getElementById('myChart').getContext('2d');
        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: datasetLabel,
                    data: data,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: (context) => {
                        const index = context.dataIndex;
                        const value = context.dataset.data[index];
                        const previousValue = context.dataset.data[index - 1];
                        return index === 0 || value >= previousValue ? 'rgba(75, 192, 192, 1)' : 'rgba(255, 99, 132, 1)';
                    },
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { 
                        display: true, 
                        title: { display: true, text: 'Date' } 
                    },
                    y: { 
                        display: true, 
                        min: 0,
                        max: goalValue,
                        title: { display: true, text: chartType === 'weight' ? 'Weight (kg)' : 'Amount' }
                    }
                },
                elements: {
                    line: {
                        tension: 0.1
                    },
                    point: {
                        radius: 3,
                        hoverRadius: 5
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', (event) => {
        updateChart();
    });
</script>
</body>
</html>
